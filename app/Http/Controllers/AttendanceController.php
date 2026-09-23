<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Make sure this matches your User or Staff model name
use App\Models\AttendanceLog;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    /**
     * 1. Displays the Main Attendance Gateway Screen
     */
   // 1. Update your index method to point inside the staff folder
public function index()
{
    // 1. Fetch your staff directory
    $staff = User::all(); 
    
    // 2. Define $todayAttendance so the template doesn't crash.
    // For now, we will pass an empty array. If you have an Attendance model later, 
    // you can replace this with: Attendance::whereDate('created_at', today())->get();
    $todayAttendance = collect([]); 

    // 3. Pass both variables to your view
    return view('staff.attendance', compact('staff', 'todayAttendance')); 
}

// 2. Update your scan method to point inside the staff folder
public function showScanTerminal(Request $request)
{
    $userId = $request->query('hidden_staff_id') ?? $request->query('user_id');

    if (!$userId) {
        return redirect('/attendance')->with('error', 'Please scan a badge or provide a valid ID.');
    }

    return view('staff.scan', compact('userId')); // Changed 'scan' to 'staff.scan'
}

    /**
     * 3. Finalizes database records on fingerprint/confirmation submission
     */
public function confirmAttendance(Request $request)
{
    if (!auth()->check() || auth()->user()->email !== 'admin@zaujati.com') {
        return redirect('/attendance')->with('error', 'Action not allowed!');
    }

    $identity = $request->input('verified_identity');
    $user = User::where('email', $identity)->orWhere('id', $identity)->first();

    if (!$user) {
        return redirect('/attendance')->with('error', 'Verification Failed: Identity not recognized.');
    }

    // Masukkan data mengikut struktur jadual 'admin_attendances' yang baru
    AttendanceLog::create([
        'user_id'      => $user->id,
        'user_type'    => 'Staff', 
        'date'         => now()->toDateString(),
        'clock_in'     => now()->toTimeString(),
        'status'       => 'Present',
    ]);

    return redirect()->route('attendance.history')->with('success', "Attendance successfully logged for {$user->name}.");
}
  public function history(Request $request)
    {
        // 🔒 Gatekeeper: Block anyone who isn't the administrator email
        if (!auth()->check() || auth()->user()->email !== 'admin@zaujati.com') {
            return redirect('/attendance')->with('error', 'Access Denied: Administrative Clearance Required.');
        }

        // Fetch logs with the associated staff/user information
        $query = AttendanceLog::with('user')->orderBy('created_at', 'desc');

        // Apply admin lookup criteria
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }

        $logs = $query->get();

        return view('staff.attendance_history', compact('logs'));
    }

   /**
 * Memadam rekod kehadiran dari pangkalan data (Admin Only)
 */
public function destroy($id)
{
    if (!auth()->check() || auth()->user()->email !== 'admin@zaujati.com') {
        return redirect('/attendance')->with('error', 'Action not allowed!');
    }

    $log = AttendanceLog::find($id);

    if (!$log) {
        return redirect()->route('attendance.history')->with('error', 'Record not found.');
    }

    $log->delete();

    // Sila pastikan baris ini tepat untuk meluncur kembali ke dashboard history
    return redirect()->route('attendance.history')->with('success', 'The attendance record has been successfully deleted.');
}

public function forceClockOut($id)
{
    // 1. 🔒 Sekatan Keselamatan Admin sahaja
    if (!auth()->check() || auth()->user()->email !== 'admin@zaujati.com') {
        return redirect('/attendance')->with('error', 'Tindakan tidak dibenarkan!');
    }

    try {
        $log = AttendanceLog::find($id);

        if (!$log) {
            return redirect()->route('attendance.history')->with('error', 'Rekod tidak dijumpai.');
        }

        // Jika sudah clock out, halang daripada clock out sekali lagi
        if ($log->clock_out !== null) {
            return redirect()->route('attendance.history')->with('error', 'Staff ini sudah pun clock out sebelum ini.');
        }

        $now = now();
        $clockInTime = Carbon::parse($log->date->format('Y-m-d') . ' ' . $log->raw_clock_in_time_placeholder_or_direct_format_here);
        
        // Ataupun cara paling selamat untuk baca nilai waktu tersimpan:
        $clockInTime = Carbon::createFromFormat('Y-m-d H:i:s', $log->date->format('Y-m-d') . ' ' . Carbon::parse($log->clock_in)->format('H:i:s'));
        $clockOutTime = $now;

        // Kira perbezaan jam bekerja (hours_worked)
        $hoursWorked = round($clockInTime->diffInMinutes($clockOutTime) / 60, 2);

        // Kemaskini rekod di database
        $log->update([
            'clock_out'    => $clockOutTime->toTimeString(),
            'status'       => 'Completed',
            'hours_worked' => $hoursWorked
        ]);

        return redirect()->route('attendance.history')->with('success', "Berjaya clock out. Jumlah jam bekerja: {$hoursWorked} jam.");

    } catch (\Exception $e) {
        return redirect()->route('attendance.history')->with('error', 'Ralat sistem: Gagal melakukan clock out. ' . $e->getMessage());
    }
}

}

