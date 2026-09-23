<?php

namespace Tests\Unit;

use App\Services\FileEncryptionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileEncryptionServiceTest extends TestCase
{
    protected FileEncryptionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->service = new FileEncryptionService();
    }

    public function test_it_encrypts_file_at_rest_with_aes_256(): void
    {
        $originalContent = 'CONFIDENTIAL MEDICAL CERTIFICATE - DR. AMIR SALLEH - MC#99281';
        $file = UploadedFile::fake()->createWithContent('mc_slip.txt', $originalContent);

        $result = $this->service->encryptAndStore($file);

        $this->assertNotEmpty($result['path']);
        $this->assertEquals('mc_slip.txt', $result['original_name']);

        // Assert file exists on disk
        $this->assertTrue(Storage::disk('local')->exists($result['path']));

        // Assert raw disk content is ciphertext (does NOT contain plaintext)
        $rawCiphertext = Storage::disk('local')->get($result['path']);
        $this->assertStringNotContainsString('CONFIDENTIAL', $rawCiphertext);
        $this->assertStringNotContainsString('MEDICAL CERTIFICATE', $rawCiphertext);

        // Assert decryption restores original plaintext exactly
        $decrypted = $this->service->decrypt($result['path']);
        $this->assertEquals($originalContent, $decrypted);
    }

    public function test_it_detects_ciphertext_tampering_via_hmac(): void
    {
        $file = UploadedFile::fake()->createWithContent('secret.txt', 'Sensitive Payroll Ledger');
        $result = $this->service->encryptAndStore($file);

        // Intentionally tamper with 1 byte in the stored ciphertext on disk
        $rawCiphertext = Storage::disk('local')->get($result['path']);
        $flippedByte = chr(ord($rawCiphertext[20]) ^ 0xFF);
        $tamperedCiphertext = substr_replace($rawCiphertext, $flippedByte, 20, 1);
        Storage::disk('local')->put($result['path'], $tamperedCiphertext);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('HMAC integrity check failed');

        $this->service->decrypt($result['path']);
    }
}
