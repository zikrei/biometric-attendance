<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_user_id',
        'punch_time',
        'punch_state',
    ];

    // A biometric log belongs to a user (linking via the device ID)
    public function user()
    {
        return $this->belongsTo(User::class, 'device_user_id', 'device_user_id');
    }
}