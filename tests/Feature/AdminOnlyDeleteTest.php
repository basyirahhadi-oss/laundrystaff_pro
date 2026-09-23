<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\Leave;
use App\Models\Staff;
use App\Models\StaffPayroll;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOnlyDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_cannot_delete_attendance_records(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $attendance = Attendance::create([
            'user_id' => $staff->id,
            'date' => now()->toDateString(),
            'status' => 'present',
        ]);

        $response = $this->actingAs($staff)->delete(route('admin.attendance.destroy', $attendance));
        $response->assertStatus(403);
        $this->assertDatabaseHas('attendances', ['id' => $attendance->id]);
    }

    public function test_staff_cannot_delete_admin_leave_applications(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $leave = Leave::create([
            'user_id' => $staff->id,
            'leave_type' => 'annual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'reason' => 'Rest',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($staff)->delete(route('admin.leaves.destroy', $leave));
        $response->assertStatus(403);
        $this->assertDatabaseHas('leaves', ['id' => $leave->id]);
    }

    public function test_staff_cannot_delete_security_audit_blocks_or_clear_ledger(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);
        $service = app(AuditLogService::class);

        $log = $service->recordEvent('attendance_clock_in', $admin->id, $admin->id, ['test' => true]);

        // Staff tries to delete single block
        $response1 = $this->actingAs($staff)->delete(route('admin.security.destroy', $log));
        $response1->assertStatus(403);
        $this->assertDatabaseHas('audit_logs', ['id' => $log->id]);

        // Staff tries to clear all logs
        $response2 = $this->actingAs($staff)->post(route('admin.security.clear-all'));
        $response2->assertStatus(403);
        $this->assertDatabaseHas('audit_logs', ['id' => $log->id]);
    }

    public function test_staff_cannot_delete_other_staff_profiles(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        
        \Illuminate\Support\Facades\DB::table('staff')->insert([
            'staff_id' => 'STF99',
            'full_name' => 'Ahmad Test',
            'position' => 'Washer',
            'phone_number' => '0123456789',
            'salary_rate' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($staff)->delete(route('staff.destroy', 'STF99'));
        $response->assertStatus(403);
        $this->assertDatabaseHas('staff', ['staff_id' => 'STF99']);
    }

    public function test_staff_cannot_delete_payroll_records(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        
        $payrollId = \Illuminate\Support\Facades\DB::table('payrolls')->insertGetId([
            'staff_id' => 'STF99',
            'month_year' => 'August 2026',
            'basic_salary' => 1500,
            'ot_hours' => 0,
            'ot_pay' => 0,
            'epf_deduction' => 165,
            'socso_deduction' => 7.25,
            'eis_deduction' => 3,
            'net_salary' => 1324.75,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($staff)->delete(route('staff.payroll.destroy', $payrollId));
        $response->assertStatus(403);
        $this->assertDatabaseHas('payrolls', ['id' => $payrollId]);
    }
}
