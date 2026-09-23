<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_single_audit_block_and_chain_is_recalculated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = app(AuditLogService::class);

        $block1 = $service->recordEvent('attendance_clock_in', $admin->id, $admin->id, ['time' => '08:00']);
        $block2 = $service->recordEvent('attendance_clock_out', $admin->id, $admin->id, ['time' => '17:00']);
        $block3 = $service->recordEvent('payroll_processed', $admin->id, null, ['amount' => 2000]);

        $response = $this->actingAs($admin)->delete(route('admin.security.destroy', $block2));

        $response->assertRedirect();
        $this->assertDatabaseMissing('audit_logs', ['id' => $block2->id]);

        // Chain should remain valid after recalculation
        $check = $service->verifyChainIntegrity();
        $this->assertTrue($check['is_valid']);
        $this->assertEquals(2, $check['verified_blocks']);
    }

    public function test_admin_can_clear_all_logs_and_reset_ledger(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = app(AuditLogService::class);

        $service->recordEvent('attendance_clock_in', $admin->id, $admin->id, ['test' => true]);
        $service->recordEvent('leave_approved', $admin->id, $admin->id, ['test' => true]);

        $this->assertEquals(2, AuditLog::count());

        $response = $this->actingAs($admin)->post(route('admin.security.clear-all'));

        $response->assertRedirect();
        
        // Ledger should have 1 fresh genesis block
        $this->assertEquals(1, AuditLog::count());
        $this->assertDatabaseHas('audit_logs', [
            'event_type' => 'ledger_reset',
        ]);

        $check = $service->verifyChainIntegrity();
        $this->assertTrue($check['is_valid']);
    }
}
