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

    // Insert attendance record into ledger
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
 * Delete attendance record from database (Admin Only)
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

    // Redirect back to attendance history ledger
    return redirect()->route('attendance.history')->with('success', 'The attendance record has been successfully deleted.');
}

public function forceClockOut($id)
{
    // 1. 🔒 Security restriction: Admin only
    if (!auth()->check() || auth()->user()->email !== 'admin@zaujati.com') {
        return redirect('/attendance')->with('error', 'Unauthorized action!');
    }

    try {
        $log = AttendanceLog::find($id);

        if (!$log) {
            return redirect()->route('attendance.history')->with('error', 'Record not found.');
        }

        // If already clocked out, prevent duplicate clock out
        if ($log->clock_out !== null) {
            return redirect()->route('attendance.history')->with('error', 'This staff member has already clocked out.');
        }

        $now = now();
        $clockInTime = Carbon::createFromFormat('Y-m-d H:i:s', $log->date->format('Y-m-d') . ' ' . Carbon::parse($log->clock_in)->format('H:i:s'));
        $clockOutTime = $now;

        // Calculate hours worked
        $hoursWorked = round($clockInTime->diffInMinutes($clockOutTime) / 60, 2);

        // Update record in database
        $log->update([
            'clock_out'    => $clockOutTime->toTimeString(),
            'status'       => 'Completed',
            'hours_worked' => $hoursWorked
        ]);

        return redirect()->route('attendance.history')->with('success', "Clock out successful. Total hours worked: {$hoursWorked} hours.");

    } catch (\Exception $e) {
        return redirect()->route('attendance.history')->with('error', 'System error: Failed to process clock out. ' . $e->getMessage());
    }
}

}

