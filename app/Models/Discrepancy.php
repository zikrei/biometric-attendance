<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discrepancy extends Model
{
        protected $fillable = [
        'attendance_id', 'type', 'user_note', 'document_path', 'status', 'hod_remark'
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
