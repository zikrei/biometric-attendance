<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; // <-- REQUIRED: Tells the controller where to find the Attendance table!

class AttendanceController extends Controller
{
    // Show all attendance records
    public function index()
    {
        // 1. Fetch all attendance records from the database
        // (Using 'with("user")' ensures it grabs the employee's name too, preventing errors!)
        $attendances = Attendance::with('user')->get();

        // 2. Pass the $attendances variable to your view
        return view('attendance.list', compact('attendances'));
    }

    // Show form to create new attendance record
    public function create()
    {
        return view('attendance.create');
    }

    // Store new attendance record
    public function store(Request $request)
    {
        // Logic to store attendance record
        return redirect()->route('attendance.list');
    }

    // Show form to edit attendance record
    public function edit($id)
    {
        return view('attendance.edit', compact('id'));
    }

    // Update attendance record
    public function update(Request $request, $id)
    {
        // Logic to update attendance record
        return redirect()->route('attendance.list');
    }

    // Show attendance history
    public function history()
    {
        return view('attendance.history');
    }
}