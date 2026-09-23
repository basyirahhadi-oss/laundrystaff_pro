<?php

namespace Tests\Feature;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StaffPayrollTest extends TestCase
{
    use RefreshDatabase;

    protected User $staffUser1;
    protected User $staffUser2;
    protected User $adminUser;
    protected int $payroll1Id;
    protected int $payroll2Id;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staffUser1 = User::factory()->create([
            'name' => 'Ahmad Razak',
            'email' => 'ahmad@example.com',
            'role' => 'staff',
        ]);

        $this->staffUser2 = User::factory()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'role' => 'staff',
        ]);

        $this->adminUser = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Insert staff profile rows
        DB::table('staff')->insert([
            [
                'user_id'         => $this->staffUser1->id,
                'staff_id'        => 'STF001',
                'full_name'       => 'Ahmad Razak',
                'position'        => 'Dobi Operator',
                'phone_number'    => '0123456789',
                'salary_rate'     => 1800.00,
                'profile_picture' => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'user_id'         => $this->staffUser2->id,
                'staff_id'        => 'STF002',
                'full_name'       => 'Siti Nurhaliza',
                'position'        => 'Kaunter Eksekutif',
                'phone_number'    => '0198765432',
                'salary_rate'     => 2200.00,
                'profile_picture' => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);

        // Insert payroll records
        $this->payroll1Id = DB::table('payrolls')->insertGetId([
            'staff_id'        => 'STF001',
            'month_year'      => 'July 2026',
            'basic_salary'    => 1800.00,
            'ot_hours'        => 10.00,
            'ot_pay'          => 129.80,
            'epf_deduction'   => 198.00,
            'socso_deduction' => 9.00,
            'eis_deduction'   => 3.60,
            'net_salary'      => 1719.20,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $this->payroll2Id = DB::table('payrolls')->insertGetId([
            'staff_id'        => 'STF002',
            'month_year'      => 'August 2026',
            'basic_salary'    => 2200.00,
            'ot_hours'        => 5.00,
            'ot_pay'          => 79.35,
            'epf_deduction'   => 242.00,
            'socso_deduction' => 11.00,
            'eis_deduction'   => 4.40,
            'net_salary'      => 2021.95,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    public function test_staff_can_view_their_payroll_index_page_with_own_payrolls(): void
    {
        $response = $this->actingAs($this->staffUser1)->get(route('staff.payroll.index'));

        $response->assertStatus(200);
        $response->assertSee('My Payroll &amp; Payslips', false);
        $response->assertSee('Ahmad Razak');
        $response->assertSee('STF001');
        $response->assertSee('July 2026');
        $response->assertSee('1,719.20');
        $response->assertSee('129.80');
    }

    public function test_staff_only_sees_their_own_payrolls_and_not_others(): void
    {
        $response = $this->actingAs($this->staffUser1)->get(route('staff.payroll.index'));

        $response->assertStatus(200);
        $response->assertSee('July 2026');
        // Staff 1 must NOT see Staff 2's payroll
        $response->assertDontSee('August 2026');
        $response->assertDontSee('2,021.95');
    }

    public function test_staff_can_print_their_own_payslip(): void
    {
        $response = $this->actingAs($this->staffUser1)->get(route('staff.payroll.print', $this->payroll1Id));

        $response->assertStatus(200);
        $response->assertSee('MONTHLY SALARY VOUCHER');
        $response->assertSee('Ahmad Razak');
        $response->assertSee('STF001');
        $response->assertSee('July 2026');
        $response->assertSee('1,719.20');
    }

    public function test_staff_cannot_view_or_print_another_staff_payslip_idor_protection(): void
    {
        // Staff 1 attempts to access Staff 2's payslip
        $response = $this->actingAs($this->staffUser1)->get(route('staff.payroll.print', $this->payroll2Id));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_and_print_any_staff_payslip(): void
    {
        $response1 = $this->actingAs($this->adminUser)->get(route('staff.payroll.print', $this->payroll1Id));
        $response1->assertStatus(200);
        $response1->assertSee('Ahmad Razak');

        $response2 = $this->actingAs($this->adminUser)->get(route('staff.payroll.print', $this->payroll2Id));
        $response2->assertStatus(200);
        $response2->assertSee('Siti Nurhaliza');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('staff.payroll.index'));
        $response->assertRedirect(route('login'));

        $printResponse = $this->get(route('staff.payroll.print', $this->payroll1Id));
        $printResponse->assertRedirect(route('login'));
    }

    public function test_sidebar_displays_my_payroll_link_for_staff(): void
    {
        $response = $this->actingAs($this->staffUser1)->get(route('staff.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('My Payroll');
    }
}
