<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    /**
     * Staff dashboard — shows today's clock in/out state and
     * a short recent-history list.
     */
    public function index()
    {
        $user = auth()->user();

        $todayAttendance = Attendance::forUser($user->id)->today()->first();

        $recentAttendances = Attendance::forUser($user->id)
            ->orderByDesc('date')
            ->limit(10)
            ->get();

        $now = Carbon::today();
        $monthlyAttendances = Attendance::forUser($user->id)
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->get();

        $monthlyHours = round($monthlyAttendances->sum('worked_hours'), 1);
        $monthlyShifts = $monthlyAttendances->count();
        $onTimeShifts = $monthlyAttendances->where('status', 'present')->count();
        $punctualityRate = $monthlyShifts > 0 ? round(($onTimeShifts / $monthlyShifts) * 100) : 100;
        $pendingLeaves = \App\Models\Leave::forUser($user->id)->pending()->count();

        return view('staff.dashboard', [
            'todayAttendance'    => $todayAttendance,
            'recentAttendances'  => $recentAttendances,
            'monthlyHours'       => $monthlyHours,
            'monthlyShifts'      => $monthlyShifts,
            'punctualityRate'    => $punctualityRate,
            'pendingLeaves'      => $pendingLeaves,
        ]);
    }

    /**
     * Clock in the currently authenticated staff member.
     * No Staff ID typing needed — identity comes from the session.
     */
    public function clockIn(): RedirectResponse
    {
        $user = auth()->user();
        $today = Carbon::today();

        $existing = Attendance::forUser($user->id)->today()->first();

        if ($existing) {
            return back()->with('error', 'You have already clocked in today.');
        }

        $now = Carbon::now();

        // Example cutoff: anything after 09:15 is marked late.
        // Adjust to match your company's shift policy.
        $lateCutoff = $today->copy()->setTime(9, 15);
        $status = $now->greaterThan($lateCutoff) ? 'late' : 'present';

        Attendance::create([
            'user_id'       => $user->id,
            'date'          => $today,
            'clock_in_time' => $now,
            'status'        => $status,
        ]);

        $message = $status === 'late'
            ? 'Clocked in successfully — marked as Late.'
            : 'Clocked in successfully!';

        return back()->with('success', $message);
    }

    /**
     * Clock out the currently authenticated staff member.
     */
    public function clockOut(): RedirectResponse
    {
        $user = auth()->user();

        $attendance = Attendance::forUser($user->id)->today()->first();

        if (!$attendance) {
            return back()->with('error', 'You have not clocked in today yet.');
        }

        if ($attendance->clock_out_time) {
            return back()->with('error', 'You have already clocked out today.');
        }

        $attendance->update([
            'clock_out_time' => Carbon::now(),
        ]);

        return back()->with('success', 'Clocked out successfully. Have a great rest of your day!');
    }
}
