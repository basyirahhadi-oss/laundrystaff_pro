<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payrolls';

    protected $guarded = [];

    protected $casts = [
        'basic_salary'    => 'decimal:2',
        'ot_hours'        => 'decimal:2',
        'ot_pay'          => 'decimal:2',
        'epf_deduction'   => 'decimal:2',
        'socso_deduction' => 'decimal:2',
        'eis_deduction'   => 'decimal:2',
        'net_salary'      => 'decimal:2',
    ];

    /**
     * Scope query to only payrolls belonging to a specific staff_id.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Total statutory deductions (EPF + SOCSO + EIS).
     */
    public function getTotalStatutoryAttribute(): float
    {
        return (float) ($this->epf_deduction + $this->socso_deduction + $this->eis_deduction);
    }
}
