<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // We removed 'status', 'reason', and 'attachment' because they moved to the Justifications table!
    protected $fillable = [
        'user_id', 
        'date', 
        'clock_in', 
        'clock_out', 
    ];

    // 1. Attendance belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. NEW: Attendance links to ONE manual justification (if they were absent/late)
    public function justification()
    {
        return $this->hasOne(AttendanceJustification::class);
    }
}