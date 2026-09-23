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
    Schema::create('staff', function (Blueprint $table) {
        $table->id();
        $table->string('staff_id')->unique(); // Contoh: STF001
        $table->string('full_name');
        $table->string('position'); // Contoh: Operator Mesin, Pekerja Kaunter
        $table->string('phone_number');
        $table->decimal('salary_rate', 8, 2); // Kadar gaji
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
