<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * PHASE 1: DATA STRUCTURE & MASS-ASSIGNMENT CONFIGURATION
     * OBJECTIVE: Define the primary schema mapping and permit bulk data injection.
     * ATTRIBUTES: 
     * - user_id: Foreign key linking to the employee.
     * - date: The specific calendar day for the record.
     * - clock_in/out: Time-specific markers for the shift duration.
     * - status: The finalized state of the attendance (e.g., Present, Absent).
     */
    protected $fillable = [
        'user_id', 
        'date', 
        'clock_in', 
        'clock_out', 
        'status'
    ];

    /**
     * PHASE 2: PRIMARY RELATIONAL MAPPING
     * OBJECTIVE: Establish the ownership of the attendance record.
     * TYPE: Inverse One-to-Many (belongsTo).
     * OUTCOME: Allows the system to retrieve the specific 'User' object associated with an attendance entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * PHASE 3: EXTENDED ATTRIBUTE LINKAGE
     * OBJECTIVE: Connect attendance discrepancies to their relevant administrative explanations.
     * TYPE: One-to-One (hasOne).
     * PROCEDURES: Links this record to the 'AttendanceJustification' model for discrepancy review.
     */
    public function justification()
    {
        return $this->hasOne(AttendanceJustification::class);
    }
}