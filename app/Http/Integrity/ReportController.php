<?php

namespace App\Http\Controllers\Integrity;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;

class ReportController extends Controller
{
    public function index()
    {
        $reports = MonthlyReport::where('status', 'Verified')
            ->latest()
            ->paginate(15);

        return view('integrity.reports.index', compact('reports'));
    }

    public function show($id)
    {
        $report = MonthlyReport::findOrFail($id);

        return view('integrity.reports.show', compact('report'));
    }

    public function review($id)
    {
        $report = MonthlyReport::findOrFail($id);

        $report->update([
            'status' => 'Reviewed',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report reviewed successfully.');
    }

    public function export()
    {
        $reports = MonthlyReport::where('status', 'Reviewed')->get();

        return view('integrity.reports.export', compact('reports'));
    }
}