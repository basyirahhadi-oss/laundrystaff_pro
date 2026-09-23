<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_their_personal_performance_analytics(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        // Create some attendance records
        Attendance::create([
            'user_id' => $staff->id,
            'date' => now()->toDateString(),
            'clock_in_time' => now()->setTime(8, 55),
            'clock_out_time' => now()->setTime(17, 0),
            'status' => 'present',
        ]);

        Attendance::create([
            'user_id' => $staff->id,
            'date' => now()->subDay()->toDateString(),
            'clock_in_time' => now()->subDay()->setTime(9, 25),
            'clock_out_time' => now()->subDay()->setTime(17, 0),
            'status' => 'late',
        ]);

        // Create an approved leave
        Leave::create([
            'user_id' => $staff->id,
            'leave_type' => 'annual',
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->subDays(4)->toDateString(),
            'reason' => 'Family vacation',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($staff)->get(route('staff.analytics.index'));

        $response->assertStatus(200);
        $response->assertSee('My Analytics &amp; Performance', false);
        $response->assertSee('Punctuality Score');
        $response->assertSee('Shift Hours');
        $response->assertSee('Annual Leave (AL)');
    }

    public function test_guest_cannot_view_staff_analytics(): void
    {
        $response = $this->get(route('staff.analytics.index'));
        $response->assertRedirect(route('login'));
    }
}
