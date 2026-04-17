<?php

namespace App\Http\Controllers;

use App\Models\MonthlyReport;
use Illuminate\Http\Request;

class MonthlyReportController extends Controller
{
    public function index()
    {
        $reports = MonthlyReport::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'report_month' => ['required', 'integer', 'between:1,12'],
            'report_year' => ['required', 'integer'],
        ]);

        MonthlyReport::create([
            'user_id' => auth()->id(),
            'department_id' => auth()->user()->department_id,
            'report_month' => $data['report_month'],
            'report_year' => $data['report_year'],
            'status' => 'Draft',
        ]);

        return redirect()->route('reports.index')
            ->with('success', 'Monthly report generated.');
    }

    public function submit($id)
    {
        $report = MonthlyReport::where('user_id', auth()->id())->findOrFail($id);

        $report->update([
            'status' => 'Submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Report submitted to HOD.');
    }

    public function show($id)
    {
        $report = MonthlyReport::where('user_id', auth()->id())->findOrFail($id);

        return view('reports.show', compact('report'));
    }
}