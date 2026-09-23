<?php

namespace Tests\Unit;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuditLogService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuditLogService();
    }

    public function test_it_records_cryptographically_chained_blocks(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        // Block 1 (Genesis child)
        $block1 = $this->service->recordEvent('attendance_clock_in', $user->id, $user->id, [
            'method' => 'face_id',
            'time'   => '08:55:00',
        ]);

        $this->assertEquals(AuditLogService::GENESIS_HASH, $block1->previous_hash);
        $this->assertEquals(64, strlen($block1->current_hash));

        // Block 2
        $block2 = $this->service->recordEvent('payroll_processed', $user->id, null, [
            'net_salary' => 1850.50,
            'month'      => 'August 2026',
        ]);

        $this->assertEquals($block1->current_hash, $block2->previous_hash);
        $this->assertNotEquals($block1->current_hash, $block2->current_hash);

        // Verify chain integrity
        $check = $this->service->verifyChainIntegrity();
        $this->assertTrue($check['is_valid']);
        $this->assertEquals(2, $check['verified_blocks']);
    }

    public function test_it_detects_database_tampering_instantly(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $block1 = $this->service->recordEvent('attendance_clock_in', $user->id, $user->id, ['time' => '08:50']);
        $block2 = $this->service->recordEvent('payroll_processed', $user->id, null, ['net_salary' => 1500.00]);

        // Simulating direct MySQL modification (e.g. Rogue admin changes net_salary to 999999 without updating hash)
        \Illuminate\Support\Facades\DB::table('audit_logs')
            ->where('id', $block2->id)
            ->update([
                'record_data' => json_encode(['net_salary' => 999999.00]),
            ]);

        // Chain scan must immediately flag failure
        $check = $this->service->verifyChainIntegrity();

        $this->assertFalse($check['is_valid']);
        $this->assertEquals($block2->id, $check['broken_block_id']);
        $this->assertStringContainsString('Cryptographic Hash Mismatch', $check['error_message']);
    }
}
