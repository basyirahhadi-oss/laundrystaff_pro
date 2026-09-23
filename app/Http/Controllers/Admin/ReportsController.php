<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $now = now();

        // ── Attendance for the current month ─────────────────────────
        $monthAttendances = Attendance::whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->get();

        $totalAttendanceRecords = $monthAttendances->count();
        $presentOrLate = $monthAttendances->whereIn('status', ['present', 'late'])->count();
        $lateArrivals = $monthAttendances->where('status', 'late')->count();

        $attendanceRate = $totalAttendanceRecords > 0
            ? round(($presentOrLate / $totalAttendanceRecords) * 100)
            : 0;

        // Status breakdown, e.g. ['present' => 42, 'late' => 6, ...]
        $statusCounts = $monthAttendances->countBy('status');
        $attendanceOverview = collect(['present', 'late', 'absent', 'on_leave', 'mc'])
            ->mapWithKeys(fn ($status) => [
                $status => [
                    'count' => $statusCounts->get($status, 0),
                    'percent' => $totalAttendanceRecords > 0
                        ? round(($statusCounts->get($status, 0) / $totalAttendanceRecords) * 100)
                        : 0,
                ],
            ]);

        // ── Leave applications submitted this month ──────────────────
        $monthLeaves = Leave::whereMonth('start_date', $now->month)
            ->whereYear('start_date', $now->year)
            ->get();

        $leaveTypeCounts = $monthLeaves->countBy('leave_type');
        $totalLeaveApplications = $monthLeaves->count();
        $leaveTypeDistribution = collect(['annual', 'mc', 'emergency', 'unpaid'])
            ->mapWithKeys(fn ($type) => [
                $type => [
                    'count' => $leaveTypeCounts->get($type, 0),
                    'percent' => $totalLeaveApplications > 0
                        ? round(($leaveTypeCounts->get($type, 0) / $totalLeaveApplications) * 100)
                        : 0,
                ],
            ]);

        $leaveDaysThisMonth = $monthLeaves
            ->where('status', 'approved')
            ->sum(fn ($leave) => $leave->start_date->diffInDays($leave->end_date) + 1);

        // ── Payroll processed this month ──────────────────────────────
        $payrollProcessed = DB::table('payrolls')
            ->where('month_year', $now->format('F Y'))
            ->sum('net_salary');

        // ── Staff requiring attention ──────────────────────────────────
        // Rule 1: 3 or more late clock-ins this month.
        $frequentlyLate = $monthAttendances
            ->where('status', 'late')
            ->groupBy('user_id')
            ->filter(fn ($rows) => $rows->count() >= 3)
            ->map(fn ($rows) => [
                'user_id' => $rows->first()->user_id,
                'reason' => $rows->count() . ' late clock-ins this month',
            ]);

        // Rule 2: any pending leave application.
        $pendingLeaveStaff = Leave::where('status', 'pending')
            ->get()
            ->map(fn ($leave) => [
                'user_id' => $leave->user_id,
                'reason' => 'Pending ' . $leave->getLeaveTypeLabelAttribute() . ' application',
            ]);

        $staffRequiringAttention = $frequentlyLate
            ->values()
            ->concat($pendingLeaveStaff)
            ->map(function ($item) {
                $staffRecord = DB::table('staff')->where('user_id', $item['user_id'])->first();
                $item['name'] = $staffRecord->full_name ?? 'Unknown Staff';
                return $item;
            })
            ->unique(fn ($item) => $item['name'] . $item['reason'])
            ->values();

        return view('admin.reports.index', [
            'totalStaff' => DB::table('staff')->count(),
            'attendanceRate' => $attendanceRate,
            'leaveDaysThisMonth' => $leaveDaysThisMonth,
            'payrollProcessed' => $payrollProcessed,
            'lateArrivals' => $lateArrivals,
            'leaveTypeDistribution' => $leaveTypeDistribution,
            'attendanceOverview' => $attendanceOverview,
            'staffRequiringAttention' => $staffRequiringAttention,
        ]);
    }
}