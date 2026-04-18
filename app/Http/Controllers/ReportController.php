<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // <-- REQUIRED: Tells the controller where to find the users!

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
        // Logic to generate report
        return view('reports.preview');
    }

    // Show the generated report
    public function show($id)
    {
        // Logic to show specific report
        return view('reports.show');
    }

    // Print the report
    public function print($id)
    {
        // Logic to print the report
        return view('reports.print');
    }

    // Export the report to PDF
    public function export($id)
    {
        // Logic to export the report to PDF
        return response()->download('report.pdf');
    }
}