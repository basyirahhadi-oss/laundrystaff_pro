<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAttendance extends Model
{
    use HasFactory;

    // Explicit table specification
    protected $table = 'admin_attendances';

    // Mass-assignable attributes
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
    ];
}