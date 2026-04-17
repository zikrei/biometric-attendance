<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        return view('dashboard');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function hodDashboard()
    {
        return view('hod.dashboard');
    }
}