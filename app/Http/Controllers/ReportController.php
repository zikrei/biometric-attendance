<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // <-- REQUIRED: Tells the controller where to find the users!
use Barryvdh\DomPDF\Facade\Pdf; // <-- Add this to unlock PDF features!

class ReportController extends Controller
{
    // Show the report generation page
    public function index()
    {
        // 1. Fetch all users from the database, sorted alphabetically by name
        $users = User::orderBy('name')->get();

        // 2. Pass the $users variable to your view using compact()
        return view('reports.index', compact('users'));
    }

    // Generate the report based on user input
    public function generate(Request $request)
    {
        // 1. Grab the filters from the form submission URL
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $department = $request->input('department');
        $userId = $request->input('user');

        // 2. Create an empty array for attendances so the table loop doesn't crash 
        // (We will add the real database query here later!)
        $attendances = [];

        // 3. Pass all this data to the preview view
        return view('reports.preview', compact('from_date', 'to_date', 'department', 'userId', 'attendances'));
    }

    // Show the generated report
    public function show($id)
    {
        // Logic to show specific report
        return view('reports.show');
    }

    // Print the report
    public function print(Request $request)
    {
        // We will grab the dates here later!
        return view('reports.print');
    }

    // Export the report to PDF
    public function export(Request $request)
    {
        // 1. Grab the date filters from the URL
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        // 2. Placeholder for attendances (we will query the DB later)
        $attendances = [];

        // 3. Load a special PDF view and pass the data to it
        $pdf = Pdf::loadView('reports.pdf', compact('from_date', 'to_date', 'attendances'));

        // 4. Download the file instantly!
        return $pdf->download('Attendance_Report.pdf');
    }
}