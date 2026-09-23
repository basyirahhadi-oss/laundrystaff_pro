<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {}

    /**
     * Monitor daily/monthly attendance logs for all staff, with
     * worked-hours calculated per row.
     */
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $staffId = $request->input('staff_id');

        $range = Carbon::createFromFormat('Y-m', $month);

        $query = Attendance::with('staff')
            ->betweenDates(
                $range->copy()->startOfMonth()->toDateString(),
                $range->copy()->endOfMonth()->toDateString()
            )
            ->orderByDesc('date');

        if ($staffId) {
            $query->forUser($staffId);
        }

        $attendances = $query->paginate(20)->withQueryString();

        $staffList = User::where('role', 'staff')->orderBy('name')->get();

        return view('admin.attendance.index', compact('attendances', 'staffList', 'month', 'staffId'));
    }

    /**
     * Manually correct or backfill a clock-in/out entry — e.g. a
     * staff member forgot to scan.
     */
    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $validated = $request->validate([
            'clock_in_time'  => ['nullable', 'date_format:H:i'],
            'clock_out_time' => ['nullable', 'date_format:H:i', 'after_or_equal:clock_in_time'],
            'status'         => ['required', 'in:present,late,absent,on_leave,mc'],
            'remarks'        => ['nullable', 'string', 'max:255'],
        ]);

        $date = $attendance->date->toDateString();

        $attendance->update([
            'clock_in_time'  => $validated['clock_in_time']
                ? Carbon::parse("{$date} {$validated['clock_in_time']}")
                : null,
            'clock_out_time' => $validated['clock_out_time']
                ? Carbon::parse("{$date} {$validated['clock_out_time']}")
                : null,
            'status'    => $validated['status'],
            'remarks'   => $validated['remarks'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        // Cryptographically Chained Audit Trail
        $this->auditLogService->recordEvent('attendance_manual_edit', $attendance->user_id, auth()->id(), [
            'attendance_id'  => $attendance->id,
            'date'           => $date,
            'clock_in_time'  => $validated['clock_in_time'],
            'clock_out_time' => $validated['clock_out_time'],
            'status'         => $validated['status'],
            'remarks'        => $validated['remarks'] ?? null,
        ]);

        return back()->with('success', "Attendance record for " . ($attendance->staff->name ?? 'this staff member') . " updated.");
    }

    /**
     * Create a manual attendance row for a staff member who has
     * no entry for a given date at all (e.g. fully missed scan).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'        => ['required', 'exists:users,id'],
            'date'           => ['required', 'date'],
            'clock_in_time'  => ['nullable', 'date_format:H:i'],
            'clock_out_time' => ['nullable', 'date_format:H:i', 'after_or_equal:clock_in_time'],
            'status'         => ['required', 'in:present,late,absent,on_leave,mc'],
            'remarks'        => ['nullable', 'string', 'max:255'],
        ]);

        $exists = Attendance::forUser($validated['user_id'])
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'An attendance record already exists for that staff member on that date.');
        }

        $newRecord = Attendance::create([
            'user_id'        => $validated['user_id'],
            'date'           => $validated['date'],
            'clock_in_time'  => $validated['clock_in_time']
                ? Carbon::parse("{$validated['date']} {$validated['clock_in_time']}")
                : null,
            'clock_out_time' => $validated['clock_out_time']
                ? Carbon::parse("{$validated['date']} {$validated['clock_out_time']}")
                : null,
            'status'         => $validated['status'],
            'remarks'        => $validated['remarks'] ?? null,
            'updated_by'     => auth()->id(),
        ]);

        // Cryptographically Chained Audit Trail
        $this->auditLogService->recordEvent('attendance_manual_add', $validated['user_id'], auth()->id(), [
            'attendance_id'  => $newRecord->id,
            'date'           => $validated['date'],
            'clock_in_time'  => $validated['clock_in_time'],
            'clock_out_time' => $validated['clock_out_time'],
            'status'         => $validated['status'],
            'remarks'        => $validated['remarks'] ?? null,
        ]);

        return back()->with('success', 'Attendance record created.');
    }

    /**
     * Delete an attendance record from the ledger.
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        $staffName = $attendance->staff->name ?? 'Staff member';
        $userId = $attendance->user_id;
        $date = $attendance->date ? $attendance->date->toDateString() : null;
        $attendanceId = $attendance->id;

        $attendance->delete();

        // Cryptographically Chained Audit Trail
        $this->auditLogService->recordEvent('attendance_deleted', $userId, auth()->id(), [
            'attendance_id' => $attendanceId,
            'date'          => $date,
            'deleted_by'    => auth()->user()->name ?? 'Admin',
        ]);

        return back()->with('success', "Attendance record for {$staffName} on {$date} has been deleted.");
    }
}
