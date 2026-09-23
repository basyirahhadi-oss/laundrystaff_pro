<?php

namespace Tests\Feature;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLeaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_leave_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);

        $leave = Leave::create([
            'user_id'    => $staff->id,
            'leave_type' => 'annual',
            'start_date' => now()->toDateString(),
            'end_date'   => now()->addDays(2)->toDateString(),
            'reason'     => 'Personal matters',
            'status'     => 'pending',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.leaves.destroy', $leave));

        $response->assertRedirect();
        $this->assertDatabaseMissing('leaves', ['id' => $leave->id]);

        $this->assertDatabaseHas('audit_logs', [
            'event_type' => 'leave_deleted',
            'user_id'    => $staff->id,
            'actor_id'   => $admin->id,
        ]);
    }
}
