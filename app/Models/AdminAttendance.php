<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAttendance extends Model
{
    use HasFactory;

    // Sila pastikan nama table adalah betul
    protected $table = 'admin_attendances';

    // 🌟 TAMBAH BARIS INI: Kebenaran untuk mass assignment
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
    ];
}