<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'department',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // User has many attendance records
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}