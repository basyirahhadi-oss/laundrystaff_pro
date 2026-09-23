<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Services\FileEncryptionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureFileController extends Controller
{
    public function __construct(
        protected FileEncryptionService $encryptionService
    ) {}

    /**
     * Stream a decrypted leave attachment directly in memory.
     * Accessible only by Admins or the Staff member who owns the leave request.
     */
    public function streamLeaveAttachment(Request $request, Leave $leave): StreamedResponse
    {
        $user = auth()->user();

        // RBAC Authorization Check
        if ($user->role !== 'admin' && $user->id !== $leave->user_id) {
            abort(403, 'Unauthorized access to encrypted personnel document.');
        }

        if (empty($leave->attachment)) {
            abort(404, 'No attachment on file for this leave request.');
        }

        $mimeType = $leave->attachment_mime ?: null;
        $originalName = $leave->attachment_name ?: basename($leave->attachment);

        try {
            return $this->encryptionService->streamDecrypted(
                $leave->attachment,
                $mimeType,
                $originalName
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Secure file stream error for Leave #{$leave->id}: " . $e->getMessage());
            abort(404, 'Document could not be decrypted or located.');
        }
    }
}
