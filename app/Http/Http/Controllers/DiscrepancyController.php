<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Discrepancy;
use Illuminate\Http\Request;

class DiscrepancyController extends Controller
{
    public function index()
    {
        $discrepancies = Discrepancy::whereHas('attendance', function ($query) {
            $query->where('user_id', auth()->id());
        })->latest()->paginate(10);

        return view('discrepancies.index', compact('discrepancies'));
    }

    public function create()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->where('status', 'Discrepancy')
            ->get();

        return view('discrepancies.create', compact('attendances'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_id' => ['required', 'exists:attendances,id'],
            'type' => ['required', 'string', 'max:255'],
            'user_note' => ['required', 'string'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $path = null;

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('discrepancies', 'public');
        }

        Discrepancy::create([
            'attendance_id' => $data['attendance_id'],
            'type' => $data['type'],
            'user_note' => $data['user_note'],
            'document_path' => $path,
            'status' => 'Pending',
        ]);

        return redirect()->route('discrepancies.index')
            ->with('success', 'Discrepancy submitted successfully.');
    }

    public function show($id)
    {
        $discrepancy = Discrepancy::findOrFail($id);

        return view('discrepancies.show', compact('discrepancy'));
    }
}