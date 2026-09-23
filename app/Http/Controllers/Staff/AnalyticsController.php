<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display comprehensive personal performance analytics and attendance statistics
     * for the authenticated staff member.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);

        // Fetch staff profile record (if linked)
        $staffProfile = DB::table('staff')
            ->where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        $staffId = $staffProfile->staff_id ?? null;
        $hourlyRate = $staffProfile->salary_rate ?? 10.00;

        // 1. Current Month Attendances
        $monthlyAttendances = Attendance::forUser($user->id)
            ->betweenDates($monthDate->copy()->startOfMonth()->toDateString(), $monthDate->copy()->endOfMonth()->toDateString())
            ->orderByDesc('date')
            ->get();

        // 2. Attendance Status Breakdown (Present, Late, Absent, On Leave, MC)
        $presentCount = $monthlyAttendances->where('status', 'present')->count();
        $lateCount = $monthlyAttendances->where('status', 'late')->count();
        $absentCount = $monthlyAttendances->where('status', 'absent')->count();
        $onLeaveCount = $monthlyAttendances->whereIn('status', ['on_leave', 'mc'])->count();
        $totalShifts = $monthlyAttendances->count();

        // 3. Punctuality & Performance Reliability Score
        $onTimeShifts = $presentCount;
        $punctualityRate = $totalShifts > 0 ? round(($onTimeShifts / $totalShifts) * 100, 1) : 100.0;

        $performanceTier = match (true) {
            $punctualityRate >= 95 => ['grade' => 'A+', 'label' => 'Excellent Standing', 'color' => 'emerald', 'icon' => 'fa-trophy'],
            $punctualityRate >= 85 => ['grade' => 'A', 'label' => 'High Reliability', 'color' => 'indigo', 'icon' => 'fa-star'],
            $punctualityRate >= 70 => ['grade' => 'B', 'label' => 'Standard Consistency', 'color' => 'amber', 'icon' => 'fa-thumbs-up'],
            default => ['grade' => 'C', 'label' => 'Attention Needed', 'color' => 'rose', 'icon' => 'fa-triangle-exclamation'],
        };

        // 4. Hours Worked & Overtime (OT)
        $totalWorkedHours = round($monthlyAttendances->sum('worked_hours'), 1);
        $standardTargetHours = 160.0; // Standard 20 shifts x 8 hours
        $hoursProgress = min(100, round(($totalWorkedHours / $standardTargetHours) * 100, 1));
        
        $avgDailyHours = $totalShifts > 0 ? round($totalWorkedHours / $totalShifts, 1) : 0.0;
        
        // Estimated Overtime
        $estimatedOTHours = max(0, round($totalWorkedHours - ($totalShifts * 8), 1));
        $estimatedGrossEarnings = round($totalWorkedHours * $hourlyRate, 2);

        // 5. Leave Entitlements & Usage (YTD)
        $leavesYtd = Leave::forUser($user->id)
            ->whereYear('start_date', $monthDate->year)
            ->get();

        $annualLeaveApproved = $leavesYtd->where('leave_type', 'annual')->where('status', 'approved')->sum(fn($l) => $l->duration_in_days);
        $mcApproved = $leavesYtd->where('leave_type', 'mc')->where('status', 'approved')->sum(fn($l) => $l->duration_in_days);
        $emergencyApproved = $leavesYtd->where('leave_type', 'emergency')->where('status', 'approved')->sum(fn($l) => $l->duration_in_days);
        $pendingLeavesCount = $leavesYtd->where('status', 'pending')->count();

        $annualLeaveTotal = 14;
        $mcTotal = 14;
        $annualLeaveBalance = max(0, $annualLeaveTotal - $annualLeaveApproved);
        $mcBalance = max(0, $mcTotal - $mcApproved);

        // 6. Recent Official Payslips
        $recentPayrolls = $staffId
            ? DB::table('payrolls')->where('staff_id', $staffId)->orderByDesc('created_at')->limit(6)->get()
            : collect();

        return view('staff.analytics', compact(
            'user',
            'staffProfile',
            'selectedMonth',
            'monthDate',
            'monthlyAttendances',
            'presentCount',
            'lateCount',
            'absentCount',
            'onLeaveCount',
            'totalShifts',
            'punctualityRate',
            'performanceTier',
            'totalWorkedHours',
            'standardTargetHours',
            'hoursProgress',
            'avgDailyHours',
            'estimatedOTHours',
            'hourlyRate',
            'estimatedGrossEarnings',
            'annualLeaveTotal',
            'annualLeaveApproved',
            'annualLeaveBalance',
            'mcTotal',
            'mcApproved',
            'mcBalance',
            'emergencyApproved',
            'pendingLeavesCount',
            'recentPayrolls'
        ));
    }
}
