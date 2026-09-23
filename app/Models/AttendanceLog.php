<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    // 1. 🔑 PAKSA Laravel untuk menggunakan table 'admin_attendances' anda!
    protected $table = 'admin_attendances';

    // 2. Benarkan mass-assignment mengikut struktur table baru
    protected $fillable = [
        'user_id',
        'user_type',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'hours_worked'
    ];

    // Beritahu Laravel untuk membaca field ini sebagai format masa/tarikh automatik
    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i:s',
        'clock_out' => 'datetime:H:i:s',
    ];

    /**
     * Hubungan dengan table Users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}