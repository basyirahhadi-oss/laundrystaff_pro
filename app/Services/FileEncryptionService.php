<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileEncryptionService
{
    /**
     * Storage disk for encrypted files (kept private, outside web root).
     */
    protected const DISK = 'local';
    protected const DIRECTORY = 'encrypted-attachments';

    /**
     * Encrypt an uploaded file with AES-256-CBC and write it to private disk.
     *
     * @param  UploadedFile  $file
     * @return array{path: string, original_name: string, mime_type: string, size: int}
     */
    public function encryptAndStore(UploadedFile $file): array
    {
        $rawContent = file_get_contents($file->getRealPath());

        // Generate a cryptographically secure 16-byte IV
        $iv = random_bytes(16);
        $key = substr(hash('sha256', config('app.key')), 0, 32);

        // Encrypt using AES-256-CBC
        $encrypted = openssl_encrypt(
            $rawContent,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Calculate HMAC to prevent ciphertext tampering
        $hmac = hash_hmac('sha256', $iv . $encrypted, $key, true);

        // Package payload: 16-byte IV + 32-byte HMAC + ciphertext
        $payload = $iv . $hmac . $encrypted;

        // Generate unique filename on private storage
        $filename = Str::uuid()->toString() . '.enc';
        $fullPath = self::DIRECTORY . '/' . $filename;

        Storage::disk(self::DISK)->put($fullPath, $payload);

        return [
            'path'          => $fullPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getClientMimeType() ?: 'application/octet-stream',
            'size'          => $file->getSize(),
        ];
    }

    /**
     * Encrypt raw string/binary content with AES-256-CBC and store it.
     */
    public function encryptRaw(string $rawContent, string $originalName = 'document.pdf'): array
    {
        $iv = random_bytes(16);
        $key = substr(hash('sha256', config('app.key')), 0, 32);

        $encrypted = openssl_encrypt(
            $rawContent,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        $hmac = hash_hmac('sha256', $iv . $encrypted, $key, true);
        $payload = $iv . $hmac . $encrypted;

        $filename = Str::uuid()->toString() . '.enc';
        $fullPath = self::DIRECTORY . '/' . $filename;

        Storage::disk(self::DISK)->put($fullPath, $payload);

        return [
            'path'          => $fullPath,
            'original_name' => $originalName,
            'mime_type'     => $this->detectMimeFromExtension($originalName),
            'size'          => strlen($rawContent),
        ];
    }

    /**
     * Decrypt an encrypted file from private storage in memory.
     * Gracefully falls back to reading legacy plaintext files on public storage if present.
     *
     * @param  string  $path
     * @return string Raw binary data
     * @throws \RuntimeException
     */
    public function decrypt(string $path): string
    {
        // 1. Check if file exists on private local storage
        if (Storage::disk(self::DISK)->exists($path)) {
            $payload = Storage::disk(self::DISK)->get($path);

            // If it is an encrypted .enc payload (has 16-byte IV + 32-byte HMAC)
            if (str_ends_with($path, '.enc') && strlen($payload) >= 48) {
                $iv = substr($payload, 0, 16);
                $storedHmac = substr($payload, 16, 32);
                $ciphertext = substr($payload, 48);

                $key = substr(hash('sha256', config('app.key')), 0, 32);

                $calculatedHmac = hash_hmac('sha256', $iv . $ciphertext, $key, true);

                if (!hash_equals($storedHmac, $calculatedHmac)) {
                    throw new \RuntimeException("HMAC integrity check failed! File may have been tampered with.");
                }

                $decrypted = openssl_decrypt(
                    $ciphertext,
                    'AES-256-CBC',
                    $key,
                    OPENSSL_RAW_DATA,
                    $iv
                );

                if ($decrypted === false) {
                    throw new \RuntimeException("AES-256 decryption failed.");
                }

                return $decrypted;
            }

            // If on local storage but plaintext
            return $payload;
        }

        // 2. Check if this is a legacy unencrypted file on public disk
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->get($path);
        }

        throw new \RuntimeException("Attachment file [{$path}] not found on storage.");
    }

    /**
     * Stream decrypted file to browser with secure security headers.
     *
     * @param  string  $path
     * @param  string|null  $mimeType
     * @param  string|null  $originalName
     * @return StreamedResponse
     */
    public function streamDecrypted(string $path, ?string $mimeType = null, ?string $originalName = null): StreamedResponse
    {
        $decryptedData = $this->decrypt($path);

        $name = $originalName ?: basename($path);
        $mime = $mimeType;

        if (empty($mime) || $mime === 'application/octet-stream') {
            $mime = $this->detectMimeFromData($decryptedData, $name);
        }

        return response()->stream(
            function () use ($decryptedData) {
                echo $decryptedData;
            },
            200,
            [
                'Content-Type'           => $mime,
                'Content-Disposition'   => 'inline; filename="' . addslashes($name) . '"',
                'Cache-Control'         => 'no-store, no-cache, must-revalidate, private',
                'Pragma'                => 'no-cache',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options'       => 'SAMEORIGIN',
            ]
        );
    }

    /**
     * Helper to detect MIME type from binary content or filename.
     */
    public function detectMimeFromData(string $data, string $filename): string
    {
        if (str_starts_with($data, '%PDF')) {
            return 'application/pdf';
        }

        if (str_starts_with($data, "\xFF\xD8\xFF")) {
            return 'image/jpeg';
        }

        if (str_starts_with($data, "\x89PNG\r\n\x1a\n")) {
            return 'image/png';
        }

        return $this->detectMimeFromExtension($filename);
    }

    public function detectMimeFromExtension(string $filename): string
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match ($ext) {
            'pdf'  => 'application/pdf',
            'png'  => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }

    /**
     * Delete file from disk.
     */
    public function delete(string $path): bool
    {
        if (Storage::disk(self::DISK)->exists($path)) {
            return Storage::disk(self::DISK)->delete($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
