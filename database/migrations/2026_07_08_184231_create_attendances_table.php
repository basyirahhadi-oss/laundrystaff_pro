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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id'); // Menyambungkan kehadiran dengan ID staf
            $table->date('date');       // Tarikh hari tersebut
            $table->time('clock_in')->nullable();  // Waktu masuk
            $table->time('clock_out')->nullable(); // Waktu keluar
            $table->string('status')->default('Present'); // Present, Absent, Leave
            $table->timestamps();

            // Hubungkan dengan jadual staff asal
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
