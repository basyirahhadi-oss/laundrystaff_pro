<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The original `attendances` table (2026_07_08) was created with
     * `staff_id` (string, FK to staff.staff_id), `clock_in`, `clock_out`.
     * Every controller and the Attendance model added on 2026-08-06
     * assume `user_id`, `clock_in_time`, `clock_out_time` instead — a
     * schema mismatch that breaks the entire Leave/Attendance module
     * (and the migration that runs after this one, which does
     * `->after('clock_out_time')`). This migration reconciles the two.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
        });

        // Backfill user_id for any existing rows via the staff table's
        // link back to its login account, where one exists.
        if (Schema::hasColumn('staff', 'user_id')) {
            DB::statement('
                UPDATE attendances
                SET user_id = (
                    SELECT staff.user_id FROM staff
                    WHERE staff.staff_id = attendances.staff_id
                )
                WHERE user_id IS NULL
            ');
        }

        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'clock_in') && !Schema::hasColumn('attendances', 'clock_in_time')) {
                $table->renameColumn('clock_in', 'clock_in_time');
            }
            if (Schema::hasColumn('attendances', 'clock_out') && !Schema::hasColumn('attendances', 'clock_out_time')) {
                $table->renameColumn('clock_out', 'clock_out_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'clock_in_time')) {
                $table->renameColumn('clock_in_time', 'clock_in');
            }
            if (Schema::hasColumn('attendances', 'clock_out_time')) {
                $table->renameColumn('clock_out_time', 'clock_out');
            }
            if (Schema::hasColumn('attendances', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
