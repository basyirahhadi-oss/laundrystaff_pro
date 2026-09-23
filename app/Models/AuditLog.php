<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'actor_id',
        'user_id',
        'record_data',
        'previous_hash',
        'current_hash',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'record_data' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getEventTypeLabelAttribute(): string
    {
        return match ($this->event_type) {
            'attendance_clock_in'    => 'Clock In (Kiosk)',
            'attendance_clock_out'   => 'Clock Out (Kiosk)',
            'attendance_manual_edit' => 'Attendance Record Adjusted',
            'attendance_manual_add'  => 'Manual Attendance Created',
            'leave_submitted'        => 'Leave Application Submitted',
            'leave_approved'         => 'Leave Application Approved',
            'leave_rejected'         => 'Leave Application Rejected',
            'leave_cancelled'        => 'Leave Application Cancelled',
            'payroll_processed'      => 'Payroll Generated & Saved',
            'payroll_deleted'        => 'Payroll Record Removed',
            'attendance_deleted'     => 'Attendance Record Deleted',
            'leave_deleted'          => 'Leave Application Deleted',
            'ledger_reset'           => 'Security Ledger Initialized/Reset',
            'staff_created'          => 'New Staff Profile Registered',
            'staff_updated'          => 'Staff Profile Modified',
            default                  => ucwords(str_replace('_', ' ', $this->event_type)),
        };
    }

    public function getEventBadgeColorAttribute(): string
    {
        return match ($this->event_type) {
            'attendance_clock_in', 'leave_approved' => 'emerald',
            'attendance_clock_out', 'leave_submitted' => 'sky',
            'payroll_processed', 'ledger_reset' => 'purple',
            'leave_rejected', 'payroll_deleted', 'attendance_deleted', 'leave_deleted' => 'rose',
            'attendance_manual_edit', 'attendance_manual_add' => 'amber',
            default => 'slate',
        };
    }
}
