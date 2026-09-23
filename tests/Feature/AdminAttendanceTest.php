<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_attendance_record_and_it_records_audit_trail(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);

        $attendance = Attendance::create([
            'user_id' => $staff->id,
            'date' => now()->toDateString(),
            'clock_in_time' => now(),
            'clock_out_time' => now()->addHours(8),
            'status' => 'present',
            'remarks' => 'Regular shift',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.attendance.destroy', $attendance));

        $response->assertRedirect();
        $this->assertDatabaseMissing('attendances', ['id' => $attendance->id]);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'event_type' => 'attendance_deleted',
            'user_id' => $staff->id,
            'actor_id' => $admin->id,
        ]);
    }

    public function test_staff_cannot_delete_attendance_record(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $otherStaff = User::factory()->create(['role' => 'staff']);

        $attendance = Attendance::create([
            'user_id' => $otherStaff->id,
            'date' => now()->toDateString(),
            'clock_in_time' => now(),
            'status' => 'present',
        ]);

        $response = $this->actingAs($staff)->delete(route('admin.attendance.destroy', $attendance));

        $response->assertStatus(403);
        $this->assertDatabaseHas('attendances', ['id' => $attendance->id]);
    }
}
