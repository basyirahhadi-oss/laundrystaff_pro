<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NOTE: Adjust the `after()` column names below to match whatever
     * already exists in your attendances table (e.g. clock_out_time).
     * The hasColumn() guards make this migration safe to run even if
     * some columns were already added manually.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'status')) {
                $table->enum('status', ['present', 'late', 'absent', 'on_leave', 'mc'])
                    ->default('present')
                    ->after('clock_out_time');
            }

            if (!Schema::hasColumn('attendances', 'leave_id')) {
                // Links an "on_leave"/"mc" attendance row back to the
                // approved leave application that generated it.
                $table->foreignId('leave_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('leaves')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('attendances', 'remarks')) {
                $table->string('remarks')->nullable()->after('leave_id');
            }

            if (!Schema::hasColumn('attendances', 'updated_by')) {
                // Tracks which admin manually corrected a clock-in/out
                $table->foreignId('updated_by')
                    ->nullable()
                    ->after('remarks')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('leave_id');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropColumn(['status', 'remarks']);
        });
    }
};
