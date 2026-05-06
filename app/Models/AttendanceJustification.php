<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceJustification extends Model
{
    /**
     * PHASE 1: DATA PERSISTENCE & MASS-ASSIGNMENT POLICY
     * OBJECTIVE: Define the schema boundaries for discrepancy justifications.
     * ATTRIBUTES:
     * - attendance_id: The primary foreign key linking to the parent attendance record.
     * - status: The current state of the request (e.g., pending, approved, rejected).
     * - reason: The text-based explanation provided by the user.
     * - attachment: The file path to supporting documentation/evidence.
     */
    protected $fillable = [
        'attendance_id', 
        'status', 
        'reason', 
        'attachment'
    ];

    /**
     * PHASE 2: INVERSE RELATIONAL MAPPING
     * OBJECTIVE: Establish a direct link back to the originating attendance event.
     * TYPE: Inverse One-to-Many (belongsTo).
     * OUTCOME: Enables the system to navigate from an administrative justification back to the specific clock-in/out data.
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}