<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceJustification extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'status',
        'reason',
        'attachment',
    ];

    // This justification belongs to exactly ONE attendance record
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}