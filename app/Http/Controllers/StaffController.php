<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Cache;

class StaffController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {}

    /**
     * 1. Papar Senarai Utama Staf
     */
    public function index()
    {
        $staffList = DB::table('staff')->orderBy('created_at', 'desc')->get();
        return view('staff.index', compact('staffList'));
    }


    /**
     * 2. Simpan Pendaftaran Staf Baru (Proses Borang Tambah Staf)
     */
   public function store(Request $request)
{
    $request->validate([
        'staff_id' => 'required|string|max:50|unique:staff,staff_id',
        'full_name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'phone_number' => 'required|string|max:30',
        'salary_rate' => 'required|numeric|min:0',
        'email' => 'nullable|email|max:255|unique:users,email',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    // Defence-in-depth: strip any stray HTML/script tags from free-text
    // input before it's stored, even though Blade already escapes output
    // on the way back out ({{ }} = htmlspecialchars).
    $fullName = strip_tags($request->full_name);
    $position = strip_tags($request->position);

    // Auto-provision a login account so this staff member can use the
    // user_id-based dashboard/leave system — no manual Staff ID typing.
    $email = $request->email ?: strtolower($request->staff_id) . '@' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'laundrystaff.local');

    $user = User::create([
        'name' => $fullName,
        'email' => $email,
        'password' => \Illuminate\Support\Facades\Hash::make(str()->random(16)),
        'role' => 'staff',
    ]);

    $insertData = [
        'user_id' => $user->id,
        'staff_id' => $request->staff_id,
        'full_name' => $fullName,
        'position' => $position,
        'phone_number' => $request->phone_number,
        'salary_rate' => $request->salary_rate,
        'profile_picture' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $filename = $request->staff_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/staff'), $filename);
        $insertData['profile_picture'] = $filename;
    }

    DB::table('staff')->insert($insertData);

    // Cryptographically Chained Audit Trail
    $this->auditLogService->recordEvent('staff_created', $user->id, auth()->id(), [
        'staff_id'    => $request->staff_id,
        'full_name'   => $fullName,
        'position'    => $position,
        'salary_rate' => $request->salary_rate,
    ]);

    return redirect()->route('staff.index')->with(
        'success',
        "Staff member registered successfully! Login email: {$email} — ask them to use \"Forgot your password?\" to set their own password."
    );
}
    /**
     * 3. Papar Borang Edit Staf
     */
    public function edit($id)
    {
        $staff = DB::table('staff')->where('staff_id', $id)->first();
        return view('staff.edit', compact('staff'));
    }

    /**
     * 4. Simpan Perubahan Edit Staf
     */
  public function update(Request $request, $id)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'position' => 'required|string',
        'phone_number' => 'required|string',
        'salary_rate' => 'required|numeric',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    // 1. Ambil rekod staff lama untuk tahu nama gambar asal
    $staff = DB::table('staff')->where('staff_id', $id)->first();
    
    // Default kekalkan nama gambar lama jika tiada gambar baru di-upload
    $filename = isset($staff->profile_picture) ? $staff->profile_picture : null;

    // 2. PROSES GAMBAR BARU (Jika ada muat naik)
    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        
        // Buat nama fail unik menggunakan staff_id
        $filename = $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Simpan fizikal fail terus ke folder 'public/uploads/staff'
        $file->move(public_path('uploads/staff'), $filename);

        // [Opsional] Padam fail gambar lama dari folder supaya tidak penuh
        if ($staff && !empty($staff->profile_picture)) {
            $oldImagePath = public_path('uploads/staff/' . $staff->profile_picture);
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }
    }

    // 3. Kemaskini maklumat ke dalam database
    DB::table('staff')->where('staff_id', $id)->update([
        'full_name' => $request->full_name,
        'position' => $request->position,
        'phone_number' => $request->phone_number,
        'salary_rate' => $request->salary_rate,
        'profile_picture' => $filename, // Menyimpan nama fail sahaja, selari dengan fungsi store!
        'updated_at' => now(), 
    ]);

    // Cryptographically Chained Audit Trail
    $this->auditLogService->recordEvent('staff_updated', $staff->user_id ?? null, auth()->id(), [
        'staff_id'    => $id,
        'full_name'   => $request->full_name,
        'position'    => $request->position,
        'salary_rate' => $request->salary_rate,
    ]);

    return redirect()->route('staff.index')->with('success', 'Staff updated successfully!');
}
    /**
     * 5. Padam Rekod Staf
     */
    public function destroy($id)
    {
        $staff = DB::table('staff')->where('staff_id', $id)->first();
        DB::table('staff')->where('staff_id', $id)->delete();

        if ($staff) {
            $this->auditLogService->recordEvent('staff_deleted', $staff->user_id ?? null, auth()->id(), [
                'staff_id'  => $id,
                'full_name' => $staff->full_name,
            ]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully!');
    }

    /**
     * 6. Cetak Profil Staf
     */
    public function print($id)
    {
        $staff = DB::table('staff')->where('staff_id', $id)->first();
        return view('staff.print', compact('staff'));
    }

    /**
     * 7. 🎯 Papar Borang Pengiraan Gaji Staf (Dipanggil oleh Button Salary)
     */
    public function payrollCreate(Request $request)
    {
        // Ambil id daripada URL query (?id=STF01)
        $id = $request->query('id'); 

        // Cari data staf menggunakan Query Builder DB agar selari dengan fungsi lain
        $staff = DB::table('staff')->where('staff_id', $id)->first();

        if (!$staff) {
            abort(404, 'Staff not found.');
        }

        // Pulangkan ke fail view payroll gabungan yang telah kita buat
        return view('staff.payroll', compact('staff'));
    }              

    /**
     * 8. 💾 Proses Formula & Simpan Rekod Gaji Semasa Ke Database
     */
    public function payrollStore(Request $request, $id)
    {
        $staff = DB::table('staff')->where('staff_id', $id)->first();
        if (!$staff) {
            abort(404, 'Staff not found.');
        }
        
        $basic_salary = $staff->salary_rate;
        $ot_hours = $request->input('ot_hours', 0);
        $month_year = $request->input('month') . ' ' . $request->input('year');

        // A. FORMULA OT
        $hourly_rate = ($basic_salary / 26) / 8;
        $ot_pay = $hourly_rate * 1.5 * $ot_hours;

        // B. POTONGAN STATUTORI MALAYSIA
        $epf_deduction = $basic_salary * 0.11;
        $eis_deduction = $basic_salary * 0.002;
        
        $socso_deduction = $basic_salary * 0.005;
        if ($socso_deduction > 24.75) {
            $socso_deduction = 24.75;
        }

        // C. KIRA GAJI BERSIH
        $net_salary = ($basic_salary + $ot_pay) - ($epf_deduction + $socso_deduction + $eis_deduction);

        // Simpan ke jadual payrolls
        $payrollId = DB::table('payrolls')->insertGetId([
            'staff_id' => $id,
            'month_year' => $month_year,
            'basic_salary' => $basic_salary,
            'ot_hours' => $ot_hours,
            'ot_pay' => round($ot_pay, 2),
            'epf_deduction' => round($epf_deduction, 2),
            'socso_deduction' => round($socso_deduction, 2),
            'eis_deduction' => round($eis_deduction, 2),
            'net_salary' => round($net_salary, 2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Cryptographically Chained Audit Trail
        $this->auditLogService->recordEvent('payroll_processed', $staff->user_id ?? null, auth()->id(), [
            'payroll_id'    => $payrollId,
            'staff_id'      => $id,
            'month_year'    => $month_year,
            'basic_salary'  => $basic_salary,
            'ot_pay'        => round($ot_pay, 2),
            'epf_deduction' => round($epf_deduction, 2),
            'socso_deduction' => round($socso_deduction, 2),
            'eis_deduction' => round($eis_deduction, 2),
            'net_salary'    => round($net_salary, 2),
        ]);

        // Tukar kepada route sejarah gaji anda (contohnya: staff.payroll.history)
        return redirect()->route('staff.payroll.history')->with('success', 'Payroll calculated and saved successfully for ' . $staff->full_name);
    }

    /**
     * 9. Papar Sejarah Pengiraan Gaji Semua Staf
     */
    public function payrollHistory()
    {
        $payrolls = DB::table('payrolls')
            ->join('staff', 'payrolls.staff_id', '=', 'staff.staff_id')
            ->select('payrolls.*', 'staff.full_name', 'staff.position')
            ->orderBy('payrolls.created_at', 'desc')
            ->get();

        return view('staff.payroll_history', compact('payrolls'));
    }

    /**
     * 10. Cetak Slip Gaji Rasmi Dari Sejarah
     */
    public function payrollPrint($id)
    {
        $payroll = DB::table('payrolls')
            ->join('staff', 'payrolls.staff_id', '=', 'staff.staff_id')
            ->where('payrolls.id', $id)
            ->select('payrolls.*', 'staff.full_name', 'staff.position', 'staff.phone_number')
            ->first();

        if (!$payroll) {
            abort(404, 'Payslip not found.');
        }

        return view('staff.print_payroll', compact('payroll'));
    }

    /**
     * 11. Padam Rekod Slip Gaji Dari Sejarah
     */
    public function payrollDestroy($id)
    {
        $payroll = DB::table('payrolls')->where('id', $id)->first();
        DB::table('payrolls')->where('id', $id)->delete();

        if ($payroll) {
            $staff = DB::table('staff')->where('staff_id', $payroll->staff_id)->first();
            $this->auditLogService->recordEvent('payroll_deleted', $staff->user_id ?? null, auth()->id(), [
                'payroll_id' => $id,
                'staff_id'   => $payroll->staff_id,
                'month_year' => $payroll->month_year,
                'net_salary' => $payroll->net_salary,
            ]);
        }

        return redirect()->back()->with('success', 'Rekod slip gaji berjaya dipadam.');
    }

    /**
     * 12. Papar Halaman Kehadiran Staf
     */
    public function attendanceIndex(Request $request)
    {
        $selectedStaffId = $request->query('staff_id');

        $staffList = DB::table('staff')
            ->select('staff_id', 'full_name', 'profile_picture')
            ->get();
        
        $todayDate = now()->format('Y-m-d');

        $todayAttendance = DB::table('attendances')
            ->join('staff', 'attendances.staff_id', '=', 'staff.staff_id')
            ->where('attendances.date', $todayDate)
            ->select('attendances.*', 'staff.full_name')
            ->orderBy('attendances.updated_at', 'desc')
            ->get();

        return view('staff.attendance', compact('staffList', 'todayAttendance', 'selectedStaffId'));
    }
    
    /**
     * 13. Proses Masuk Kerja (Clock In)
     */
  
    public function clockIn(Request $request)
    {
        $request->validate([
            'staff_id' => 'required'
        ]);

        $today = date('Y-m-d');
        $now = date('H:i:s');

        $exists = DB::table('attendances')
            ->where('staff_id', $request->staff_id)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already clocked in for today!'
            ]);
        }

        DB::table('attendances')->insert([
            'staff_id' => $request->staff_id,
            'date' => $today,
            'clock_in' => $now,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Clock In Successful! Have a great day ahead.'
        ]);
    }

    /**
     * 14. Proses Keluar Kerja (Clock Out)
     */
  public function clockOut(Request $request)
    {
        $request->validate([
            'staff_id' => 'required'
        ]);

        $today = date('Y-m-d');
        $now = date('H:i:s');

        $attendance = DB::table('attendances')
            ->where('staff_id', $request->staff_id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'You must Clock In first before Clocking Out!'
            ]);
        }

        if ($attendance->clock_out) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already clocked out for today!'
            ]);
        }

        DB::table('attendances')
            ->where('id', $attendance->id)
            ->update([
                'clock_out' => $now,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Clock Out Successful! Goodbye and rest well.'
        ]);
    }

    /**
     * 15. Padam Rekod Kehadiran
     */
    public function deleteAttendance($id)
    {
        $deleted = DB::table('attendances')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Attendance record deleted successfully.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete record or record not found.'
        ], 400);
    }


    /**
     * 16. Laporan Dashboard Kehadiran Admin
     */
    public function adminAttendanceDashboard(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        $staffId = $request->get('staff_id');

        $query = DB::table('attendances')
            ->join('staff', 'attendances.staff_id', '=', 'staff.staff_id')
            ->select('attendances.*', 'staff.full_name')
            ->whereMonth('attendances.date', $month)
            ->whereYear('attendances.date', $year);

        if ($staffId) {
            $query->where('attendances.staff_id', $staffId);
        }

        $attendanceRecords = $query->orderBy('attendances.date', 'desc')
                                   ->orderBy('attendances.clock_in', 'desc')
                                   ->get();

        foreach ($attendanceRecords as $record) {
            if ($record->clock_in && $record->clock_out) {
                $in = Carbon::parse($record->clock_in);
                $out = Carbon::parse($record->clock_out);
                $record->hours_worked = round($in->diffInMinutes($out) / 60, 2);
            } else {
                $record->hours_worked = 0;
            }
        }

        $allStaff = DB::table('staff')->orderBy('full_name')->get();

        $selectedMonth = $month;
        $selectedYear = $year;
        $selectedStaff = $staffId;

        return view('staff.attendance_report', compact(
            'attendanceRecords', 
            'allStaff', 
            'selectedMonth', 
            'selectedYear',
            'selectedStaff'
      ));
    }

    /**
     * 18. Kiosk Terminal — Landing Page.
     */
    public function attendanceGateway()
    {
        return view('staff.attendance_gateway');
    }

    /**
     * 19. Staff face-scan screen. Generates a cryptographically signed
     * HMAC session token for anti-replay verification and liveness checks.
     */
    public function showStaffScan()
    {
        $staffList = DB::table('staff')
            ->whereNotNull('user_id')
            ->whereNotNull('profile_picture')
            ->select('user_id', 'full_name', 'position', 'profile_picture')
            ->get()
            ->map(fn ($s) => [
                'userId' => $s->user_id,
                'name' => $s->full_name,
                'role' => 'Staff (' . $s->position . ')',
                'photoUrl' => asset('uploads/staff/' . $s->profile_picture),
            ]);

        // Generate Cryptographic One-Time Nonce Token (Anti-Replay)
        $nonce = bin2hex(random_bytes(16));
        $timestamp = time();
        $signature = hash_hmac('sha256', "kiosk_scan:{$nonce}:{$timestamp}", config('app.key'));
        
        $kioskToken = base64_encode(json_encode([
            'nonce'     => $nonce,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ]));

        // Store nonce in cache for 60 seconds (must be consumed only once)
        Cache::put("kiosk_nonce:{$nonce}", true, 60);

        return view('staff.scan', [
            'staffList'  => $staffList,
            'kioskToken' => $kioskToken,
        ]);
    }

    /**
     * 20. Staff Clock In — confirms face match, validates liveness and
     * cryptographic HMAC one-time token to defeat presentation & replay attacks.
     */
    public function confirmAttendance(Request $request)
    {
        $request->validate([
            'verified_identity' => 'required|string',
            'face_matched'      => 'required|accepted',
            'liveness_verified' => 'required|accepted',
            'kiosk_token'       => 'required|string',
        ]);

        // Cryptographic Nonce & Anti-Replay Validation
        $decoded = json_decode(base64_decode($request->kiosk_token), true);
        if (!$decoded || !isset($decoded['nonce'], $decoded['timestamp'], $decoded['signature'])) {
            return redirect()->route('kiosk.gateway')->with('error', 'Security error: Invalid cryptographic token format.');
        }

        // Check token age (valid for 60 seconds)
        if (abs(time() - (int)$decoded['timestamp']) > 60) {
            return redirect()->route('kiosk.gateway')->with('error', 'Security error: Scan session expired. Please scan again.');
        }

        // Check HMAC Signature against server APP_KEY
        $expectedSig = hash_hmac('sha256', "kiosk_scan:{$decoded['nonce']}:{$decoded['timestamp']}", config('app.key'));
        if (!hash_equals($expectedSig, $decoded['signature'])) {
            return redirect()->route('kiosk.gateway')->with('error', 'Security error: Cryptographic signature mismatch. Potential request tampering.');
        }

        // Verify that nonce has not been consumed yet (Anti-Replay)
        if (!Cache::pull("kiosk_nonce:{$decoded['nonce']}")) {
            return redirect()->route('kiosk.gateway')->with('error', 'Security error: Replay attack detected! This token has already been consumed.');
        }

        $resolved = $this->resolveKioskUser($request->input('verified_identity'));

        if (!$resolved) {
            return redirect()->route('kiosk.gateway')->with('error', 'Verification failed: identity not recognized.');
        }

        [$user, $displayName] = $resolved;

        $result = $this->recordKioskAttendance($user);

        if ($result['already_done']) {
            return redirect()->route('kiosk.gateway')
                ->with('info', "{$displayName} has already completed today's attendance cycle.");
        }

        auth()->login($user);

        return redirect()->route('staff.dashboard')->with(
            'success',
            "{$result['type']} successful at {$result['time']->format('h:i A')}. Liveness verified. Welcome, {$displayName}!"
        );
    }

    /**
     * 21. Admin Clock In — email + password screen.
     */
    public function showAdminLogin()
    {
        return view('staff.admin_login');
    }

    /**
     * 22. Verify the admin's password, record the punch, and log them in.
     */
    public function confirmAdminAttendance(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))
            ->where('role', 'admin')
            ->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
            return back()->withInput($request->only('email'))->with('error', 'Invalid admin email or password.');
        }

        $result = $this->recordKioskAttendance($user);

        if ($result['already_done']) {
            return redirect()->route('kiosk.gateway')
                ->with('info', "{$user->name} has already completed today's attendance cycle.");
        }

        auth()->login($user);

        return redirect()->route('admin.attendance.index')->with(
            'success',
            "{$result['type']} successful at {$result['time']->format('h:i A')}. Welcome, {$user->name}!"
        );
    }

    /**
     * Shared clock-in/clock-out recording logic with Cryptographic Hash Chained Audit Logging.
     */
    private function recordKioskAttendance(User $user): array
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            $status = $now->format('H:i:s') > '09:15:00' ? 'late' : 'present';

            $newAttendance = Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'clock_in_time' => $now,
                'status' => $status,
            ]);

            // Cryptographically Chained Audit Trail
            $this->auditLogService->recordEvent('attendance_clock_in', $user->id, $user->id, [
                'attendance_id' => $newAttendance->id,
                'date'          => $today,
                'clock_in_time' => $now->toDateTimeString(),
                'status'        => $status,
                'method'        => 'biometric_face_id_kiosk',
            ]);

            return ['type' => 'CLOCK IN', 'time' => $now, 'already_done' => false];
        }

        if (is_null($attendance->clock_out_time)) {
            $attendance->update(['clock_out_time' => $now]);

            // Cryptographically Chained Audit Trail
            $this->auditLogService->recordEvent('attendance_clock_out', $user->id, $user->id, [
                'attendance_id'  => $attendance->id,
                'date'           => $today,
                'clock_out_time' => $now->toDateTimeString(),
                'method'         => 'biometric_face_id_kiosk',
            ]);

            return ['type' => 'CLOCK OUT', 'time' => $now, 'already_done' => false];
        }

        return ['type' => null, 'time' => $now, 'already_done' => true];
    }

    /**
     * Resolve a kiosk identity input (Staff ID, email, or raw user id
     * matched by face recognition) to the underlying User record, plus
     * display name/role/profile-picture filename.
     *
     * @return array{0: User, 1: string, 2: string, 3: ?string}|null
     */
    private function resolveKioskUser(?string $input): ?array
    {
        if (!$input) {
            return null;
        }

        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $input)->first();

            if (!$user) {
                return null;
            }

            $staffRecord = DB::table('staff')->where('user_id', $user->id)->first();
            $role = $staffRecord ? 'Staff (' . $staffRecord->position . ')' : ($user->role === 'admin' ? 'Administrator' : 'Staff');

            return [$user, $user->name, $role, $staffRecord->profile_picture ?? null];
        }

        $staff = DB::table('staff')->where('staff_id', $input)->first();

        if ($staff && $staff->user_id) {
            $user = User::find($staff->user_id);

            if ($user) {
                return [$user, $staff->full_name, 'Staff (' . $staff->position . ')', $staff->profile_picture];
            }
        }

        if (ctype_digit($input)) {
            $user = User::find((int) $input);

            if ($user) {
                $staffRecord = DB::table('staff')->where('user_id', $user->id)->first();
                $role = $staffRecord ? 'Staff (' . $staffRecord->position . ')' : ($user->role === 'admin' ? 'Administrator' : 'Staff');

                return [$user, $user->name, $role, $staffRecord->profile_picture ?? null];
            }
        }

        return null;
    }
}