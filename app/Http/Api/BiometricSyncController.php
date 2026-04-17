<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class BiometricSyncController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'device_user_id' => ['required', 'string'],
            'record_date' => ['required', 'date'],
            'clock_in' => ['nullable'],
            'clock_out' => ['nullable'],
        ]);

        $user = User::where('device_user_id', $data['device_user_id'])->firstOrFail();

        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'record_date' => $data['record_date'],
            ],
            [
                'clock_in' => $data['clock_in'] ?? null,
                'clock_out' => $data['clock_out'] ?? null,
                'status' => 'Present',
            ]
        );

        return response()->json([
            'message' => 'Attendance synced successfully.',
            'attendance' => $attendance,
        ]);
    }
}