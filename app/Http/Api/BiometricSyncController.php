<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class BiometricSyncController extends Controller
{
    /**
     * Store and Synchronize Attendance Data
     * * This endpoint handles incoming POST requests from external biometric 
     * middleware or networked devices.
     */
    public function store(Request $request)
    {
        /**
         * PHASE 1: PAYLOAD VALIDATION & SANITIZATION
         * OBJECTIVE: Ensure incoming biometric data adheres to the required system schema.
         * PARAMETERS: 
         * - device_user_id: Required string representing the hardware-level UID.
         * - record_date: Required date object for the attendance day.
         * - clock_in/out: Nullable time strings representing the transition points.
         */
        $data = $request->validate([
            'device_user_id' => ['required', 'string'],
            'record_date' => ['required', 'date'],
            'clock_in' => ['nullable'],
            'clock_out' => ['nullable'],
        ]);

        /**
         * PHASE 2: ENTITY IDENTIFICATION
         * OBJECTIVE: Locate the internal system User associated with the external hardware ID.
         * EXCEPTION HANDLING: Utilizing 'firstOrFail()' to ensure a 404 response is returned 
         * if the biometric ID has not been mapped to a local user account.
         */
        $user = User::where('device_user_id', $data['device_user_id'])->firstOrFail();

        /**
         * PHASE 3: ATTENDANCE SYNCHRONIZATION (UPSERT)
         * OBJECTIVE: Atomically update an existing daily record or create a new entry.
         * MATCHING CRITERIA: Composite check of 'user_id' and 'record_date'.
         * DATA PERSISTENCE: 
         * - Updates clock times based on the validated payload.
         * - Automatically sets the status to 'Present' upon successful sync.
         */
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

        /**
         * PHASE 4: TRANSACTION ACKNOWLEDGEMENT
         * OBJECTIVE: Return a standardized JSON response to the requesting client/middleware.
         * PAYLOAD: Includes a success message and the finalized attendance model instance.
         */
        return response()->json([
            'message' => 'Attendance synced successfully.',
            'attendance' => $attendance,
        ]);
    }
}