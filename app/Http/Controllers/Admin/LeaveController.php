<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\ReviewLeaveRequest;
use App\Models\Attendance;
use App\Models\Leave;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {}

    /**
     * All leave applications, pending ones first, with filtering
     * by status.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $query = Leave::with('staff')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $leaves = $query->paginate(15)->withQueryString();

        return view('admin.leave.index', compact('leaves', 'status'));
    }

    /**
     * Approve a leave application. On approval, automatically
     * create/overwrite attendance rows for every day in the leave
     * range so they show as On Leave / MC instead of Absent.
     */
    public function approve(ReviewLeaveRequest $request, Leave $leave): RedirectResponse
    {
        $validated = $request->validated();

        if ($leave->status !== 'pending') {
            return back()->with('error', 'This application has already been reviewed.');
        }

        DB::transaction(function () use ($leave, $validated) {
            $leave->update([
                'status'        => 'approved',
                'admin_remarks' => $validated['admin_remarks'] ?? null,
                'approved_by'   => auth()->id(),
                'approved_at'   => now(),
            ]);

            $attendanceStatus = $leave->leave_type === 'mc' ? 'mc' : 'on_leave';

            $period = Carbon::parse($leave->start_date)->daysUntil(
                Carbon::parse($leave->end_date)->addDay()
            );

            foreach ($period as $date) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => $leave->user_id,
                        'date'    => $date->toDateString(),
                    ],
                    [
                        'status'   => $attendanceStatus,
                        'leave_id' => $leave->id,
                        'remarks'  => 'Auto-generated from approved leave application',
                    ]
                );
            }
        });

        // Cryptographically Chained Audit Trail
        $this->auditLogService->recordEvent('leave_approved', $leave->user_id, auth()->id(), [
            'leave_id'      => $leave->id,
            'leave_type'    => $leave->leave_type,
            'start_date'    => $leave->start_date->toDateString(),
            'end_date'      => $leave->end_date->toDateString(),
            'admin_remarks' => $validated['admin_remarks'] ?? null,
        ]);

        return back()->with('success', "Leave approved for " . ($leave->staff->name ?? 'this staff member') . ".");
    }

    /**
     * Reject a leave application with an optional reason.
     */
    public function reject(ReviewLeaveRequest $request, Leave $leave): RedirectResponse
    {
        $validated = $request->validated();

        if ($leave->status !== 'pending') {
            return back()->with('error', 'This application has already been reviewed.');
        }

        $leave->update([
            'status'        => 'rejected',
            'admin_remarks' => $validated['admin_remarks'] ?? null,
            'approved_by'   => auth()->id(),
            'approved_at'   => now(),
        ]);

        // Cryptographically Chained Audit Trail
        $this->auditLogService->recordEvent('leave_rejected', $leave->user_id, auth()->id(), [
            'leave_id'      => $leave->id,
            'leave_type'    => $leave->leave_type,
            'admin_remarks' => $validated['admin_remarks'] ?? null,
        ]);

        return back()->with('success', "Leave application rejected for " . ($leave->staff->name ?? 'this staff member') . ".");
    }

    /**
     * Delete a leave application record.
     */
    public function destroy(Leave $leave): RedirectResponse
    {
        $staffName = $leave->staff->name ?? 'Staff member';
        $userId = $leave->user_id;
        $leaveId = $leave->id;
        $leaveType = $leave->leave_type;

        // Delete encrypted attachment file if exists
        if ($leave->attachment && file_exists(storage_path('app/' . $leave->attachment))) {
            @unlink(storage_path('app/' . $leave->attachment));
        }

        $leave->delete();

        // Cryptographically Chained Audit Trail
        $this->auditLogService->recordEvent('leave_deleted', $userId, auth()->id(), [
            'leave_id'   => $leaveId,
            'leave_type' => $leaveType,
            'deleted_by' => auth()->user()->name ?? 'Admin',
        ]);

        return back()->with('success', "Leave application #{$leaveId} for {$staffName} has been deleted.");
    }
}
