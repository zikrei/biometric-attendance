<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. FIXED: Added the exact column names from your database migration
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role_id', 
        'department_id',
        'device_user_id', 
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    // 2. FIXED: Added the Role relationship so Auth::user()->role works!
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // 3. FIXED: Added the Department relationship
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // User has many attendance records (Kept your existing code)
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}