<?php

use App\Http\Controllers\Staff\AttendanceController as StaffAttendanceController;
use App\Http\Controllers\Staff\LeaveController as StaffLeaveController;
use App\Http\Controllers\Staff\AnalyticsController as StaffAnalyticsController;
use App\Http\Controllers\Staff\PayrollController as StaffPayrollController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\LeaveController as AdminLeaveController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\SecurityAuditController as AdminSecurityAuditController;
use App\Http\Controllers\SecureFileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Entry Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::prefix('kiosk')->name('kiosk.')->group(function () {
    Route::get('/', [StaffController::class, 'attendanceGateway'])->name('gateway');

    // Staff — face recognition + EAR liveness + HMAC anti-replay nonce
    Route::get('/scan', [StaffController::class, 'showStaffScan'])->name('scan');
    Route::post('/confirm', [StaffController::class, 'confirmAttendance'])
        ->name('confirm');

    // Admin — password-verified clock in.
    Route::get('/admin-login', [StaffController::class, 'showAdminLogin'])->name('admin-login');
    Route::post('/admin-confirm', [StaffController::class, 'confirmAdminAttendance'])
        ->name('admin-confirm');
});
/*
|--------------------------------------------------------------------------
| Role-aware "dashboard" redirect
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.attendance.index')
        : redirect()->route('staff.dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (any authenticated user, regardless of role)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Secure In-Memory Decryption Streaming for Encrypted Documents
    Route::get('/secure-files/leave/{leave}', [SecureFileController::class, 'streamLeaveAttachment'])
        ->name('secure.leave.attachment');

    // Official Payslip Printing (Accessible by Admin and authorized Staff member)
    Route::get('/staff/payroll/{id}/print', [StaffPayrollController::class, 'print'])
        ->name('staff.payroll.print');
});

/*
|--------------------------------------------------------------------------
| Staff Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', [StaffAttendanceController::class, 'index'])->name('dashboard');
        Route::post('/clock-in', [StaffAttendanceController::class, 'clockIn'])->name('clock-in');
        Route::post('/clock-out', [StaffAttendanceController::class, 'clockOut'])->name('clock-out');

        // Leave Application Module — staff-facing
        Route::get('/leaves', [StaffLeaveController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/create', [StaffLeaveController::class, 'create'])->name('leaves.create');
        Route::post('/leaves', [StaffLeaveController::class, 'store'])->name('leaves.store');
        Route::delete('/leaves/{leave}', [StaffLeaveController::class, 'destroy'])->name('leaves.destroy');

        // Personal Performance & Attendance Analytics
        Route::get('/analytics', [StaffAnalyticsController::class, 'index'])->name('analytics.index');

        // Personal Payroll & Compensation Statements — staff-facing
        Route::get('/payroll', [StaffPayrollController::class, 'index'])->name('payroll.index');
    });

/*
|--------------------------------------------------------------------------
| Admin Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [AdminAttendanceController::class, 'store'])->name('attendance.store');
        Route::put('/attendance/{attendance}', [AdminAttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{attendance}', [AdminAttendanceController::class, 'destroy'])->name('attendance.destroy');

        // Leave Application Module — admin-facing
        Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('leaves.index');
        Route::post('/leaves/{leave}/approve', [AdminLeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('/leaves/{leave}/reject', [AdminLeaveController::class, 'reject'])->name('leaves.reject');
        Route::delete('/leaves/{leave}', [AdminLeaveController::class, 'destroy'])->name('leaves.destroy');

        Route::get('/reports', [AdminReportsController::class, 'index'])->name('reports.index');

        // Cryptographic Blockchain Security & Integrity Audit Ledger
        Route::get('/security-audit', [AdminSecurityAuditController::class, 'index'])->name('security.index');
        Route::post('/security-audit/verify', [AdminSecurityAuditController::class, 'verify'])->name('security.verify');
        Route::post('/security-audit/tamper/{log}', [AdminSecurityAuditController::class, 'simulateTamper'])->name('security.tamper');
        Route::post('/security-audit/recalculate', [AdminSecurityAuditController::class, 'recalculateChain'])->name('security.recalculate');
        Route::delete('/security-audit/{log}', [AdminSecurityAuditController::class, 'destroy'])->name('security.destroy');
        Route::post('/security-audit/clear-all', [AdminSecurityAuditController::class, 'clearAll'])->name('security.clear-all');
    });

/*
|--------------------------------------------------------------------------
| Staff Management (CRUD) & Payroll — admin-only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::get('/staff/{id}/print', [StaffController::class, 'print'])->name('staff.print');

    Route::get('/staff/payroll/history', [StaffController::class, 'payrollHistory'])->name('staff.payroll.history');
    Route::get('/staff/payroll/create', [StaffController::class, 'payrollCreate'])->name('staff.payroll.create');
    Route::post('/staff/payroll/store/{id}', [StaffController::class, 'payrollStore'])->name('staff.payroll.store');
    Route::delete('/staff/payroll/{id}', [StaffController::class, 'payrollDestroy'])->name('staff.payroll.destroy');
});

require __DIR__.'/auth.php';

// NOTE: resources/views/staff/attendance.blade.php, attendance_report.blade.php,
// staff_dashboard.blade.php, attendance_history.blade.php, and the old public
// Kiosk Terminal in app/Http/Controllers/StaffController.php (attendanceGateway/
// scan/storeAttendance) and app/Http/Controllers/AttendanceController.php are
// legacy, pre-redesign files. None of them are routed here — they're orphaned,
// not reachable, and their internal route() calls to old names like
// 'zaujati.attendance.gateway' are dead code. They still write to the OLD
// staff_id/clock_in schema, which no longer matches the attendances table
// after this update's schema-fix migration, so they'd need a rewrite before
// being wired back up. Left in place only in case you still want that
// kiosk/shared-terminal flow — flagging rather than restoring it broken.
