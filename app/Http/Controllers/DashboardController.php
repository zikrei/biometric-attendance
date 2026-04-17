<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('admin.dashboard');
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