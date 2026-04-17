<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // <-- Added the User model import

class DashboardController extends Controller
{
    // Show User Dashboard
    public function userDashboard()
    {
        return view('user.dashboard');
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
        return view('hod.dashboard');
    }

    // Show Integrity Unit Dashboard
    public function integrityDashboard()
    {
        return view('integrity.dashboard');
    }
}