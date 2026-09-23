<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The original attendances table defined `staff_id` as NOT NULL with
     * no default. Now that clock-in/out writes use `user_id` instead,
     * `staff_id` is no longer populated on new rows — so it must become
     * nullable, or every new insert fails a NOT NULL constraint.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('staff_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('staff_id')->nullable(false)->change();
        });
    }
};