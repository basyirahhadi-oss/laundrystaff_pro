<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\StoreLeaveRequest;
use App\Models\Leave;
use App\Services\AuditLogService;
use App\Services\FileEncryptionService;
use Illuminate\Http\RedirectResponse;

class LeaveController extends Controller
{
    public function __construct(
        protected FileEncryptionService $encryptionService,
        protected AuditLogService $auditLogService
    ) {}

    /**
     * List the authenticated staff member's own leave applications
     * with real-time status.
     */
    public function index()
    {
        $leaves = Leave::forUser(auth()->id())
            ->latest()
            ->paginate(10);

        return view('staff.leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('staff.leaves.create');
    }

    public function store(StoreLeaveRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $attachmentPath = null;
        $attachmentName = null;
        $attachmentMime = null;

        // AES-256 At-Rest Encryption for uploaded MC / attachment
        if ($request->hasFile('attachment')) {
            $encryptionResult = $this->encryptionService->encryptAndStore($request->file('attachment'));
            $attachmentPath = $encryptionResult['path'];
            $attachmentName = $encryptionResult['original_name'];
            $attachmentMime = $encryptionResult['mime_type'];
        }

        $leave = Leave::create([
            'user_id'         => auth()->id(),
            'leave_type'      => $validated['leave_type'],
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'],
            'reason'          => $validated['reason'],
            'attachment'      => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_mime' => $attachmentMime,
            'status'          => 'pending',
        ]);

        // Cryptographically Chained Audit Trail
        $this->auditLogService->recordEvent('leave_submitted', auth()->id(), auth()->id(), [
            'leave_id'       => $leave->id,
            'leave_type'     => $leave->leave_type,
            'start_date'     => $leave->start_date->toDateString(),
            'end_date'       => $leave->end_date->toDateString(),
            'has_attachment' => !empty($attachmentPath),
        ]);

        return redirect()
            ->route('staff.leaves.index')
            ->with('success', 'Leave application submitted securely with encrypted attachments. You will be notified once reviewed.');
    }

    /**
     * Staff may cancel their own leave application, but only
     * while it's still pending.
     */
    public function destroy(Leave $leave): RedirectResponse
    {
        abort_unless($leave->user_id === auth()->id(), 403);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be cancelled.');
        }

        if ($leave->attachment) {
            $this->encryptionService->delete($leave->attachment);
        }

        $this->auditLogService->recordEvent('leave_cancelled', auth()->id(), auth()->id(), [
            'leave_id'   => $leave->id,
            'leave_type' => $leave->leave_type,
        ]);

        $leave->delete();

        return back()->with('success', 'Leave application cancelled.');
    }
}
