<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->latest('record_date')
            ->paginate(15);

        return view('attendance.index', compact('attendances'));
    }

    public function show($id)
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->findOrFail($id);

        return view('attendance.show', compact('attendance'));
    }

    public function printMonthly(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $attendances = Attendance::where('user_id', auth()->id())
            ->whereMonth('record_date', $month)
            ->whereYear('record_date', $year)
            ->get();

        return view('attendance.print', compact('attendances', 'month', 'year'));
    }
}