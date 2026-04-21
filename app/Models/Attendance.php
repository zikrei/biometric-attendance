<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Added 'reason' and 'attachment' to allow the database to save them!
    protected $fillable = [
        'user_id', 
        'date', 
        'clock_in', 
        'clock_out', 
        'status',
        'reason',
        'attachment'
    ];

    // Attendance belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}