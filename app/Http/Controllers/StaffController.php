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
use Illuminate\Http\UploadedFile;

class StaffController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {}

    /**
     * 1. Display Staff Directory
     */
    public function index()
    {
        $staffList = DB::table('staff')
            ->select('id', 'user_id', 'staff_id', 'full_name', 'position', 'phone_number', 'salary_rate', 'profile_picture', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('staff.index', compact('staffList'));
    }


    /**
     * 2. Store New Staff Registration (Process New Staff Form)
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
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $fullName = strip_tags($request->full_name);
        $position = strip_tags($request->position);

        $email = $request->email ?: strtolower($request->staff_id) . '@' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'laundrystaff.local');

        // Clean up orphaned user record if a previous attempt failed midway (e.g. before Vercel fix)
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && !DB::table('staff')->where('user_id', $existingUser->id)->exists()) {
            $existingUser->delete();
        }

        $profilePicture = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $this->processProfilePicture($request->file('profile_picture'), $request->staff_id);
            $this->ensureProfilePictureColumnIsText();
        }

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
            'profile_picture' => $profilePicture,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        try {
            DB::table('staff')->insert($insertData);
        } catch (\Throwable $e) {
            $user->delete();
            throw $e;
        }

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
     * 3. Display Staff Edit Form
     */
    public function edit($id)
    {
        $staff = DB::table('staff')->where('staff_id', $id)->first();
        return view('staff.edit', compact('staff'));
    }

    /**
     * 4. Save Staff Profile Updates
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

    // 1. Fetch existing staff record to check previous picture filename
    $staff = DB::table('staff')->where('staff_id', $id)->first();
    
    // Default: preserve existing photo if no new image uploaded
    $filename = isset($staff->profile_picture) ? $staff->profile_picture : null;

    // 2. Process new profile picture upload
    if ($request->hasFile('profile_picture')) {
        $filename = $this->processProfilePicture($request->file('profile_picture'), $id);
        $this->ensureProfilePictureColumnIsText();

        // Delete old physical image file from storage if applicable (non-Data URI)
        if ($staff && !empty($staff->profile_picture) && !str_starts_with($staff->profile_picture, 'data:image')) {
            $oldImagePath = public_path('uploads/staff/' . $staff->profile_picture);
            if (file_exists($oldImagePath) && is_writable($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }
    }

    // 3. Update staff details in database
    DB::table('staff')->where('staff_id', $id)->update([
        'full_name' => $request->full_name,
        'position' => $request->position,
        'phone_number' => $request->phone_number,
        'salary_rate' => $request->salary_rate,
        'profile_picture' => $filename,
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
     * 5. Delete Staff Record
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
     * 6. Print Staff Profile
     */
    public function print($id)
    {
        $staff = DB::table('staff')->where('staff_id', $id)->first();
        return view('staff.print', compact('staff'));
    }

    /**
     * 7. Display Staff Payroll Calculation Form
     */
    public function payrollCreate(Request $request)
    {
        // Get staff ID from URL query (?id=STF01)
        $id = $request->query('id'); 

        // Fetch staff profile using Query Builder
        $staff = DB::table('staff')->where('staff_id', $id)->first();

        if (!$staff) {
            abort(404, 'Staff not found.');
        }

        // Return combined payroll view
        return view('staff.payroll', compact('staff'));
    }              

    /**
     * 8. Process Payroll Formula & Save Statement to Database
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

        // A. OVERTIME FORMULA
        $hourly_rate = ($basic_salary / 26) / 8;
        $ot_pay = $hourly_rate * 1.5 * $ot_hours;

        // B. MALAYSIAN STATUTORY DEDUCTIONS
        $epf_deduction = $basic_salary * 0.11;
        $eis_deduction = $basic_salary * 0.002;
        
        $socso_deduction = $basic_salary * 0.005;
        if ($socso_deduction > 24.75) {
            $socso_deduction = 24.75;
        }

        // C. COMPUTE NET SALARY
        $net_salary = ($basic_salary + $ot_pay) - ($epf_deduction + $socso_deduction + $eis_deduction);

        // Save to payrolls ledger
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

        // Redirect to payroll records ledger
        return redirect()->route('staff.payroll.history')->with('success', 'Payroll calculated and saved successfully for ' . $staff->full_name);
    }

    /**
     * 9. Display All Processed Payroll Records
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
     * 10. Print Official Salary Voucher
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
     * 11. Delete Payslip Record from Ledger
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

        return redirect()->back()->with('success', 'Payslip record deleted successfully.');
    }

    /**
     * 12. Display Staff Attendance Terminal
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
     * 13. Process Staff Clock In
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
     * 14. Process Staff Clock Out
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
     * 15. Delete Attendance Record
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
     * 16. Admin Attendance Report & Dashboard
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
            ->select('user_id', 'staff_id', 'full_name', 'position', 'profile_picture')
            ->get()
            ->map(fn ($s) => [
                'userId' => $s->user_id,
                'name' => $s->full_name,
                'role' => 'Staff (' . $s->position . ')',
                'photoUrl' => route('staff.avatar', $s->staff_id),
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

        // Store nonce in cache for 300 seconds (must be consumed only once)
        Cache::put("kiosk_nonce:{$nonce}", true, 300);

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

        // Check token age (valid for 300 seconds / 5 minutes)
        if (abs(time() - (int)$decoded['timestamp']) > 300) {
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

    /**
     * Stream staff avatar image with HTTP caching.
     * Prevents Base64 payload inflation in serverless functions (Vercel 4.5MB payload limit).
     */
    public function avatar(string $staffId)
    {
        $staff = DB::table('staff')->where('staff_id', $staffId)->first();
        if (!$staff || empty($staff->profile_picture)) {
            return $this->fallbackAvatar($staff ? $staff->full_name : 'Staff');
        }

        return $this->streamAvatarResponse($staff->profile_picture, $staff->full_name);
    }

    /**
     * Stream user avatar image with HTTP caching.
     */
    public function userAvatar($userId)
    {
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) {
            return $this->fallbackAvatar('User');
        }

        $staff = DB::table('staff')->where('user_id', $userId)->first();
        if (!$staff && !empty($user->email) && str_contains($user->email, '@')) {
            $prefix = explode('@', $user->email)[0];
            $staff = DB::table('staff')
                ->where('staff_id', strtoupper($prefix))
                ->orWhere('staff_id', $prefix)
                ->first();
        }

        if ($staff && !empty($staff->profile_picture)) {
            return $this->streamAvatarResponse($staff->profile_picture, $user->name);
        }

        return $this->fallbackAvatar($user->name);
    }

    /**
     * Stream avatar image from Base64, disk, or remote URL.
     */
    protected function streamAvatarResponse(string $pictureData, string $fallbackName)
    {
        // 1. Data URL (Base64)
        if (str_starts_with($pictureData, 'data:image')) {
            if (preg_match('/^data:(image\/[a-zA-Z0-9\+\-\.]+);base64,(.+)$/', $pictureData, $matches)) {
                $mimeType = $matches[1];
                $binary = base64_decode($matches[2]);
                $etag = md5($binary);

                if (request()->header('If-None-Match') === '"' . $etag . '"') {
                    return response('', 304);
                }

                return response($binary, 200, [
                    'Content-Type'   => $mimeType,
                    'Content-Length' => strlen($binary),
                    'Cache-Control'  => 'public, max-age=86400, stale-while-revalidate=604800',
                    'ETag'           => '"' . $etag . '"',
                ]);
            }
        }

        // 2. Direct remote URL
        if (str_starts_with($pictureData, 'http://') || str_starts_with($pictureData, 'https://')) {
            return redirect()->away($pictureData);
        }

        // 3. Local file path on disk
        $localPath = public_path('uploads/staff/' . $pictureData);
        if (file_exists($localPath) && is_file($localPath)) {
            return response()->file($localPath, [
                'Cache-Control' => 'public, max-age=86400, stale-while-revalidate=604800',
            ]);
        }

        return $this->fallbackAvatar($fallbackName);
    }

    /**
     * Generate inline SVG fallback avatar.
     */
    protected function fallbackAvatar(string $name)
    {
        $initial = strtoupper(substr(trim($name ?: 'U'), 0, 1));
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128">'
            . '<rect width="128" height="128" rx="64" fill="#4f46e5"/>'
            . '<text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" fill="#ffffff" font-family="system-ui, -apple-system, sans-serif" font-size="52" font-weight="700">' . htmlspecialchars($initial) . '</text>'
            . '</svg>';

        return response($svg, 200, [
            'Content-Type'  => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400, stale-while-revalidate=604800',
        ]);
    }

    /**
     * Process uploaded staff profile picture safely for serverless (Vercel) & local environments.
     * Compresses/downscales images to max 400x400 to keep Base64 payload under 50KB.
     * Generates a Base64 Data URL so the image is stored directly in the database and never lost
     * when serverless function instances recycle or when the filesystem is strictly read-only.
     */
    protected function processProfilePicture(UploadedFile $file, string $staffId): string
    {
        $realPath = $file->getRealPath();
        $mime = $file->getMimeType() ?: 'image/jpeg';
        $compressedData = null;

        // Downscale & compress via GD if available to keep database payload tiny (<50KB)
        if (extension_loaded('gd') && function_exists('imagecreatefromstring')) {
            try {
                $raw = file_get_contents($realPath);
                $img = @imagecreatefromstring($raw);
                if ($img !== false) {
                    $width = imagesx($img);
                    $height = imagesy($img);
                    $maxDim = 400;

                    if ($width > $maxDim || $height > $maxDim) {
                        $ratio = min($maxDim / $width, $maxDim / $height);
                        $newW = max(1, (int)round($width * $ratio));
                        $newH = max(1, (int)round($height * $ratio));
                        $resized = imagecreatetruecolor($newW, $newH);

                        if ($mime === 'image/png') {
                            imagealphablending($resized, false);
                            imagesavealpha($resized, true);
                        }

                        imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $width, $height);
                        imagedestroy($img);
                        $img = $resized;
                    }

                    ob_start();
                    if ($mime === 'image/png') {
                        imagepng($img, null, 7);
                    } else {
                        imagejpeg($img, null, 80);
                        $mime = 'image/jpeg';
                    }
                    $compressedData = ob_get_clean();
                    imagedestroy($img);
                }
            } catch (\Throwable $e) {
                $compressedData = null;
            }
        }

        if (!$compressedData) {
            $compressedData = file_get_contents($realPath);
        }

        $base64 = 'data:' . $mime . ';base64,' . base64_encode($compressedData);

        // On local environments where public/uploads/staff is writable, optionally keep a local copy
        $uploadDir = public_path('uploads/staff');
        if (!isset($_ENV['VERCEL']) && !env('VERCEL') && !getenv('VERCEL')) {
            try {
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }
                if (is_dir($uploadDir) && is_writable($uploadDir)) {
                    $filename = $staffId . '_' . time() . '.' . ($mime === 'image/png' ? 'png' : 'jpg');
                    @file_put_contents($uploadDir . DIRECTORY_SEPARATOR . $filename, $compressedData);
                }
            } catch (\Throwable $e) {
                // Ignore filesystem write errors
            }
        }

        return $base64;
    }

    /**
     * Ensure staff.profile_picture column can store long Base64 strings.
     * On PostgreSQL (Neon), auto-heals column from varchar(255) to TEXT if migration hasn't run yet.
     */
    protected function ensureProfilePictureColumnIsText(): void
    {
        static $executed = false;
        if ($executed) {
            return;
        }
        $executed = true;

        try {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('ALTER TABLE staff ALTER COLUMN profile_picture TYPE TEXT');
            }
        } catch (\Throwable $e) {
            // Silently continue if already TEXT or unprivileged
        }
    }
}