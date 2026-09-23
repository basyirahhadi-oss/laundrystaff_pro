<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in_time',
        'clock_out_time',
        'status',
        'leave_id',
        'remarks',
        'updated_by',
    ];

    protected $casts = [
        'date'           => 'date',
        'clock_in_time'  => 'datetime',
        'clock_out_time' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────

    public function staff()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    public function editedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('date', Carbon::today());
    }

    public function scopeBetweenDates(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('date', [$from, $to]);
    }

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Worked hours for this row, rounded to 2 decimals.
     */
    public function getWorkedHoursAttribute(): float
    {
        if (!$this->clock_in_time || !$this->clock_out_time) {
            return 0.0;
        }

        return round(
            $this->clock_in_time->diffInMinutes($this->clock_out_time) / 60,
            2
        );
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'present'  => 'green',
            'late'     => 'orange',
            'absent'   => 'red',
            'on_leave' => 'blue',
            'mc'       => 'purple',
            default    => 'gray',
        };
    }
}
