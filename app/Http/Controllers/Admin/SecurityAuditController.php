<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SecurityAuditController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {}

    /**
     * Display the Cryptographic Blockchain/Merkle Audit Ledger & Integrity Diagnostics.
     */
    public function index()
    {
        $integrity = $this->auditLogService->verifyChainIntegrity();

        $logs = AuditLog::with(['actor', 'targetUser'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        $totalBlocks = AuditLog::count();
        $latestBlock = AuditLog::orderBy('id', 'desc')->first();
        $genesisBlock = AuditLog::orderBy('id', 'asc')->first();

        return view('admin.security.index', compact(
            'integrity',
            'logs',
            'totalBlocks',
            'latestBlock',
            'genesisBlock'
        ));
    }

    /**
     * Trigger manual on-demand Cryptographic Chain Integrity Scan.
     */
    public function verify(): RedirectResponse
    {
        $integrity = $this->auditLogService->verifyChainIntegrity();

        if ($integrity['is_valid']) {
            return back()->with('success', "Cryptographic Integrity Verified: All {$integrity['total_blocks']} ledger blocks are 100% authentic and tamper-free.");
        }

        return back()->with('error', "CRITICAL SECURITY ALERT: Tampering detected! Block #{$integrity['broken_block_id']} hash verification failed.");
    }

    /**
     * Viva / Examiner Demonstration: Simulate database tampering on a block.
     * Alters the record data directly to demonstrate that SHA-256 hash chaining detects manual SQL edits.
     */
    public function simulateTamper(Request $request, AuditLog $log): RedirectResponse
    {
        $data = $log->record_data ?? [];
        $data['_TAMPERED_BY_INSIDER'] = 'Unauthorized modification test at ' . now()->toTimeString();
        
        // Save altered data directly without recomputing hash
        \Illuminate\Support\Facades\DB::table('audit_logs')
            ->where('id', $log->id)
            ->update([
                'record_data' => json_encode($data),
            ]);

        return back()->with('warning', "Simulation: Block #{$log->id} payload was intentionally altered in the database. Run the Integrity Scanner to see tamper detection in action!");
    }

    /**
     * Recompute and fix hash chain for demo recovery.
     */
    public function recalculateChain(): RedirectResponse
    {
        $this->auditLogService->recalculateChain();

        return back()->with('success', 'Cryptographic hash chain recalculated and restored to valid state.');
    }

    /**
     * Delete a single audit log entry and immediately rebuild hash chain.
     */
    public function destroy(AuditLog $log): RedirectResponse
    {
        $blockId = $log->id;
        $eventType = $log->event_type;

        $log->delete();

        // Recalculate chain so cryptographic integrity remains valid
        $this->auditLogService->recalculateChain();

        return back()->with('success', "Audit Block #{$blockId} ({$eventType}) deleted and blockchain ledger recalculated successfully.");
    }

    /**
     * Clear all audit logs and re-initialize the ledger.
     */
    public function clearAll(): RedirectResponse
    {
        AuditLog::truncate();

        // Initialize fresh genesis record
        $this->auditLogService->recordEvent('ledger_reset', auth()->id(), auth()->id(), [
            'action'      => 'Ledger Reset / Cleared by Administrator',
            'cleared_by'  => auth()->user()->name,
            'cleared_at'  => now()->toDateTimeString(),
        ]);

        return back()->with('success', 'All security audit logs have been cleared and a fresh Genesis ledger has been initialized.');
    }
}
