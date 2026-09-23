<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    public const GENESIS_HASH = '0000000000000000000000000000000000000000000000000000000000000000';

    /**
     * Record a cryptographically chained audit log entry (Blockchain-style Merkle log).
     *
     * @param  string  $eventType
     * @param  int|null  $userId Target user affected (e.g. staff)
     * @param  int|null  $actorId User performing the action (e.g. admin or staff self)
     * @param  array  $data Action payload
     * @return AuditLog
     */
    public function recordEvent(string $eventType, ?int $userId, ?int $actorId, array $data): AuditLog
    {
        // 1. Fetch the hash of the most recent block in the chain
        $latestBlock = AuditLog::orderBy('id', 'desc')->first();
        $previousHash = $latestBlock ? $latestBlock->current_hash : self::GENESIS_HASH;

        $now = now();
        $timestamp = $now->format('Y-m-d H:i:s');
        $ip = Request::ip();
        $userAgent = Request::userAgent();

        // 2. Compute SHA-256 fingerprint for this block
        $dataToHash = $this->serializeBlockData($previousHash, $eventType, $userId, $actorId, $data, $timestamp);
        $currentHash = hash('sha256', $dataToHash);

        // 3. Persist immutable record
        return AuditLog::create([
            'event_type'    => $eventType,
            'actor_id'      => $actorId,
            'user_id'       => $userId,
            'record_data'   => $data,
            'previous_hash' => $previousHash,
            'current_hash'  => $currentHash,
            'ip_address'    => $ip,
            'user_agent'    => $userAgent,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);
    }

    /**
     * Verify the cryptographic integrity of the entire audit chain.
     * Traverses from genesis block to the tip and re-computes all hashes.
     *
     * @return array{
     *   is_valid: bool,
     *   total_blocks: int,
     *   verified_blocks: int,
     *   broken_block_id: int|null,
     *   error_message: string|null,
     *   scanned_at: string
     * }
     */
    public function verifyChainIntegrity(): array
    {
        $logs = AuditLog::orderBy('id', 'asc')->get();
        $totalBlocks = $logs->count();

        if ($totalBlocks === 0) {
            return [
                'is_valid'         => true,
                'total_blocks'     => 0,
                'verified_blocks'  => 0,
                'broken_block_id'  => null,
                'error_message'    => 'Audit ledger is empty (Genesis state).',
                'scanned_at'       => now()->toDateTimeString(),
            ];
        }

        $expectedPreviousHash = self::GENESIS_HASH;
        $verifiedCount = 0;

        foreach ($logs as $log) {
            // Check 1: Does this block correctly point to the previous block's hash?
            if ($log->previous_hash !== $expectedPreviousHash) {
                return [
                    'is_valid'        => false,
                    'total_blocks'    => $totalBlocks,
                    'verified_blocks' => $verifiedCount,
                    'broken_block_id' => $log->id,
                    'error_message'   => "Broken Hash Link at Block #{$log->id}! Stored previous hash does not match previous block.",
                    'scanned_at'      => now()->toDateTimeString(),
                ];
            }

            // Check 2: Recompute the SHA-256 hash using stored payload & timestamp
            $recalculatedData = $this->serializeBlockData(
                $log->previous_hash,
                $log->event_type,
                $log->user_id,
                $log->actor_id,
                $log->record_data ?? [],
                $log->created_at->format('Y-m-d H:i:s')
            );
            $recalculatedHash = hash('sha256', $recalculatedData);

            if (!hash_equals($log->current_hash, $recalculatedHash)) {
                return [
                    'is_valid'        => false,
                    'total_blocks'    => $totalBlocks,
                    'verified_blocks' => $verifiedCount,
                    'broken_block_id' => $log->id,
                    'error_message'   => "Cryptographic Hash Mismatch at Block #{$log->id}! Data in this record has been altered in the database.",
                    'scanned_at'      => now()->toDateTimeString(),
                ];
            }

            $expectedPreviousHash = $log->current_hash;
            $verifiedCount++;
        }

        return [
            'is_valid'        => true,
            'total_blocks'    => $totalBlocks,
            'verified_blocks' => $verifiedCount,
            'broken_block_id' => null,
            'error_message'   => null,
            'scanned_at'      => now()->toDateTimeString(),
        ];
    }

    /**
     * Recalculate all cryptographic hashes in sequence to restore chain baseline.
     */
    public function recalculateChain(): void
    {
        $logs = AuditLog::orderBy('id', 'asc')->get();
        $previousHash = self::GENESIS_HASH;

        foreach ($logs as $log) {
            $data = $log->record_data ?? [];
            unset($data['_TAMPERED_BY_INSIDER']); // Clean demo tampering artifacts

            $timestamp = $log->created_at->format('Y-m-d H:i:s');
            $serialized = $this->serializeBlockData(
                $previousHash,
                $log->event_type,
                $log->user_id,
                $log->actor_id,
                $data,
                $timestamp
            );
            $currentHash = hash('sha256', $serialized);

            \Illuminate\Support\Facades\DB::table('audit_logs')
                ->where('id', $log->id)
                ->update([
                    'record_data'   => json_encode($data),
                    'previous_hash' => $previousHash,
                    'current_hash'  => $currentHash,
                ]);

            $previousHash = $currentHash;
        }
    }

    /**
     * Deterministic serialization format for block hashing.
     */
    public function serializeBlockData(
        string $previousHash,
        string $eventType,
        ?int $userId,
        ?int $actorId,
        array $data,
        string $timestamp
    ): string {
        // Sort keys to ensure deterministic JSON encoding
        ksort($data);

        return implode('|', [
            $previousHash,
            $eventType,
            (string) ($userId ?? '0'),
            (string) ($actorId ?? '0'),
            json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            $timestamp,
        ]);
    }
}
