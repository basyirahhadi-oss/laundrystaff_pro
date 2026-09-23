<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);

        $this->staff = User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@test.com',
            'role' => 'staff',
        ]);
    }

    public function test_admin_sees_dark_navy_sidebar_and_unified_topbar(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.attendance.index'));

        $response->assertStatus(200);
        $response->assertSee('#0B1527', false); // Dark navy sidebar background
        $response->assertSee('LaundryStaff', false);
        $response->assertSee('PRO', false);
        $response->assertSee('Admin Panel', false);
        $response->assertSee('Attendance Records', false);
        $response->assertSee('Staff Directory', false);
        $response->assertSee('Payroll', false);
        $response->assertSee('Reports', false);
        $response->assertSee('Security Audit', false);
        $response->assertSee('Kiosk Terminal', false);
        $response->assertSee('Profile Settings', false);
    }

    public function test_staff_sees_dark_navy_sidebar_and_staff_portal(): void
    {
        $response = $this->actingAs($this->staff)->get(route('staff.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('#0B1527', false);
        $response->assertSee('LaundryStaff', false);
        $response->assertSee('PRO', false);
        $response->assertSee('Staff Portal', false);
        $response->assertSee('Good', false); // Greeting
        $response->assertSee('My Leaves', false);
        $response->assertSee('My Payroll', false);
        $response->assertSee('Performance', false);
        $response->assertSee('Kiosk Terminal', false);
    }

    public function test_admin_reports_and_security_views_render_with_sidebar(): void
    {
        $reportsResp = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $reportsResp->assertStatus(200);
        $reportsResp->assertSee('#0B1527', false);
        $reportsResp->assertSee('Workforce Reports', false);

        $secResp = $this->actingAs($this->admin)->get(route('admin.security.index'));
        $secResp->assertStatus(200);
        $secResp->assertSee('#0B1527', false);
        $secResp->assertSee('Security Audit Ledger', false);
    }

    public function test_admin_payroll_and_staff_directory_render_with_sidebar(): void
    {
        $staffResp = $this->actingAs($this->admin)->get(route('staff.index'));
        $staffResp->assertStatus(200);
        $staffResp->assertSee('#0B1527', false);
        $staffResp->assertSee('Personnel Directory', false);

        $payrollResp = $this->actingAs($this->admin)->get(route('staff.payroll.history'));
        $payrollResp->assertStatus(200);
        $payrollResp->assertSee('#0B1527', false);
        $payrollResp->assertSee('Payroll Records', false);
    }

    public function test_admin_kiosk_login_screen_renders_with_brand_and_security_theme(): void
    {
        $response = $this->get(route('kiosk.admin-login'));

        $response->assertStatus(200);
        $response->assertSee('Admin Shift Check-In');
        $response->assertSee('LaundryStaff');
        $response->assertSee('PRO');
        $response->assertSee('SHA-256 Ledger');
        $response->assertSee('Rate Throttled');
    }
}
