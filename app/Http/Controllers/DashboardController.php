<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // <-- Added the User model import
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;           

class DashboardController extends Controller
{
    // Show User Dashboard
    public function userDashboard()
    {
        // 1. Get the currently logged-in user
        $user = Auth::user();

        // 2. Count their Pending Discrepancies
        $pendingDiscrepancies = Attendance::where('user_id', $user->id)
        ->whereHas('justification', function ($query) {
        $query->where('status', 'pending');
        })
    ->count();

        // 3. Calculate Attendance Percentage for the Current Month
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Get total recorded days for this user this month
        $totalRecords = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();

        // Get days they actually clocked in
        $presentRecords = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->whereNotNull('clock_in')
            ->count();

        // Prevent dividing by zero if they have no records yet!
        $attendancePercentage = $totalRecords > 0 ? round(($presentRecords / $totalRecords) * 100) : 0;

        // 4. Send the data to the dashboard view
        // (Make sure this view path matches your actual file, e.g., 'user.dashboard')
        return view('user.dashboard', compact('user', 'pendingDiscrepancies', 'attendancePercentage'));
    }

    // Show Admin Dashboard
    public function adminDashboard()
    {
        // 1. Get the currently logged-in Admin
        $user = Auth::user();

        // 2. Count the total users in the system
        $totalUsers = \App\Models\User::count();

        // 3. Return the view
        return view('admin.dashboard', compact('user', 'totalUsers'));
    }

    // Show HOD Dashboard
    public function hodDashboard()
    {
        // 1. Get the currently logged-in HOD
        $user = Auth::user();

        // 2. Count Pending Approvals for their specific department
        $pendingApprovals = Attendance::where('status', 'Pending')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('department_id', $user->department_id)
                      ->where('id', '!=', $user->id); // Exclude the HOD's own requests
            })
            ->count();

        // 3. Send the data to the HOD dashboard view
        return view('hod.dashboard', compact('user', 'pendingApprovals'));
    }

    // Integrity Unit Dashboard
    public function integrityDashboard()
    {
        // 1. Get the currently logged-in Integrity officer
        $user = Auth::user();

        // 2. Count system-wide Pending Discrepancies (ALL departments)
        $totalPending = Attendance::where('status', 'Pending')->count();

        // 3. Count system-wide Staff Attendance for Today
        $today = date('Y-m-d');
        $totalAttendanceToday = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->count();

        // 4. Return the integrity dashboard view
        return view('integrity.dashboard', compact('user', 'totalPending', 'totalAttendanceToday'));
    }
}