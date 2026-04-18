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
            ->where('status', 'Pending')
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
        // 1. Ask the database to count all registered users
        $totalUsers = User::count();

        // 2. Placeholder for Attendance Issues (Set to 0 until we link your Attendance table)
        $attendanceIssues = 0;

        // 3. Send these variables to the Blade view
        return view('admin.dashboard', compact('totalUsers', 'attendanceIssues'));
    }

    // Show HOD Dashboard
    public function hodDashboard()
    {
        // 1. Get the currently logged-in HOD
        $user = Auth::user();

        // 2. Count Pending Approvals for their specific department
        $pendingApprovals = Attendance::where('status', 'Pending')
            ->whereHas('user', function ($query) use ($user) {
                // Only look at users who share the same department_id as the HOD
                $query->where('department_id', $user->department_id);
            })
            ->count();

        // 3. Count Staff Attendance for Today (in their department)
        $today = date('Y-m-d');
        $staffAttendance = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in') // Make sure they actually clocked in
            ->whereHas('user', function ($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->count();

        // 4. Send the data to the HOD dashboard view
        return view('hod.dashboard', compact('user', 'pendingApprovals', 'staffAttendance'));
    }
}