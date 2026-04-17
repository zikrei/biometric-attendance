<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Discrepancy;
use App\Models\MonthlyReport;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function discrepancies()
    {
        $discrepancies = Discrepancy::with(['attendance.user'])
            ->where('status', 'Pending')
            ->paginate(10);

        return view('hod.discrepancies.index', compact('discrepancies'));
    }

    public function showDiscrepancy($id)
    {
        $discrepancy = Discrepancy::with(['attendance.user'])->findOrFail($id);

        return view('hod.discrepancies.show', compact('discrepancy'));
    }

    public function approveDiscrepancy(Request $request, $id)
    {
        $discrepancy = Discrepancy::findOrFail($id);

        $request->validate([
            'hod_remark' => ['nullable', 'string'],
        ]);

        $discrepancy->update([
            'status' => 'Approved',
            'hod_remark' => $request->hod_remark,
        ]);

        return back()->with('success', 'Discrepancy approved.');
    }

    public function rejectDiscrepancy(Request $request, $id)
    {
        $discrepancy = Discrepancy::findOrFail($id);

        $request->validate([
            'hod_remark' => ['required', 'string'],
        ]);

        $discrepancy->update([
            'status' => 'Rejected',
            'hod_remark' => $request->hod_remark,
        ]);

        return back()->with('success', 'Discrepancy rejected.');
    }

    public function reports()
    {
        $reports = MonthlyReport::where('status', 'Submitted')
            ->paginate(10);

        return view('hod.reports.index', compact('reports'));
    }

    public function verifyReport($id)
    {
        $report = MonthlyReport::findOrFail($id);

        $report->update([
            'status' => 'Verified',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Report verified successfully.');
    }
}