<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('payrolls', function (Blueprint $table) {
        $table->id();
        $table->string('staff_id');
        $table->string('month_year'); // Contoh: "July 2026"
        $table->decimal('basic_salary', 10, 2);
        $table->decimal('ot_hours', 5, 2)->default(0.00);
        $table->decimal('ot_pay', 10, 2)->default(0.00);
        $table->decimal('epf_deduction', 10, 2);   // 11%
        $table->decimal('socso_deduction', 10, 2); // Kadar SOCSO
        $table->decimal('eis_deduction', 10, 2);   // 0.2%
        $table->decimal('net_salary', 10, 2);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
