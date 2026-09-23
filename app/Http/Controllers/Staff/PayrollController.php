<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    /**
     * Display personal payroll history, compensation summaries, and statutory breakdown
     * for the currently authenticated staff member.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Fetch staff profile linked to this authenticated user account
        $staffProfile = DB::table('staff')
            ->where('user_id', $user->id)
            ->first();

        if (!$staffProfile) {
            return view('staff.payroll.index', [
                'staffProfile'      => null,
                'payrolls'          => collect(),
                'selectedYear'      => 'all',
                'availableYears'    => collect(),
                'latestPayroll'     => null,
                'totalNetYtd'       => 0,
                'totalOtYtd'        => 0,
                'totalStatutoryYtd' => 0,
                'totalBasicYtd'     => 0,
                'avgMonthlyNet'     => 0,
            ]);
        }

        $staffId = $staffProfile->staff_id;

        // 2. Query payrolls for this staff member
        $query = Payroll::forStaff($staffId)->orderByDesc('created_at');

        // Extract all years present in records for filter dropdown
        $allPayrolls = Payroll::forStaff($staffId)->get();
        $availableYears = $allPayrolls->map(function ($p) {
            // Check year from month_year string (e.g. "July 2026") or created_at
            if (preg_match('/\b(20\d{2})\b/', $p->month_year, $matches)) {
                return $matches[1];
            }
            return $p->created_at ? $p->created_at->format('Y') : date('Y');
        })->unique()->sortDesc()->values();

        // Filter by year if requested
        $selectedYear = $request->input('year', 'all');
        if ($selectedYear !== 'all' && is_numeric($selectedYear)) {
            $query->where(function ($q) use ($selectedYear) {
                $q->where('month_year', 'like', "%{$selectedYear}%")
                  ->orWhereYear('created_at', $selectedYear);
            });
        }

        $payrolls = $query->get();

        // 3. Aggregate Financial Metrics
        $latestPayroll = $allPayrolls->sortByDesc('created_at')->first();
        $totalNetYtd = $payrolls->sum('net_salary');
        $totalOtYtd = $payrolls->sum('ot_pay');
        $totalBasicYtd = $payrolls->sum('basic_salary');
        $totalStatutoryYtd = $payrolls->sum(function ($p) {
            return (float) ($p->epf_deduction + $p->socso_deduction + $p->eis_deduction);
        });
        $avgMonthlyNet = $payrolls->count() > 0 ? round($totalNetYtd / $payrolls->count(), 2) : 0;

        return view('staff.payroll.index', [
            'staffProfile'      => $staffProfile,
            'payrolls'          => $payrolls,
            'selectedYear'      => $selectedYear,
            'availableYears'    => $availableYears,
            'latestPayroll'     => $latestPayroll,
            'totalNetYtd'       => $totalNetYtd,
            'totalOtYtd'        => $totalOtYtd,
            'totalStatutoryYtd' => $totalStatutoryYtd,
            'totalBasicYtd'     => $totalBasicYtd,
            'avgMonthlyNet'     => $avgMonthlyNet,
        ]);
    }

    /**
     * Print or download official monthly salary voucher with strict ownership authorization.
     */
    public function print(int|string $id)
    {
        $user = auth()->user();

        // Fetch payroll with staff details
        $payroll = DB::table('payrolls')
            ->join('staff', 'payrolls.staff_id', '=', 'staff.staff_id')
            ->where('payrolls.id', $id)
            ->select('payrolls.*', 'staff.full_name', 'staff.position', 'staff.phone_number', 'staff.user_id')
            ->first();

        if (!$payroll) {
            abort(404, 'Salary slip record not found.');
        }

        // Access Control (IDOR Protection):
        // Allow if user is admin, or if this payslip belongs to the authenticated staff user
        $isOwner = $payroll->user_id && ((int)$payroll->user_id === (int)$user->id);
        $isAdmin = $user->role === 'admin';

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Unauthorized access. You are only permitted to view your own salary statements.');
        }

        return view('staff.print_payroll', compact('payroll'));
    }
}
