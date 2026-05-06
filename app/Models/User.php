<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * PHASE 1: SCHEMA CONFIGURATION & MASS-ASSIGNMENT POLICY
     * OBJECTIVE: Define the primary data attributes for user profiles and organizational placement.
     * ATTRIBUTES:
     * - name/email/password: Core authentication and identity markers.
     * - role_id/department_id: Hierarchical and structural grouping keys.
     * - device_user_id: Unique hardware identifier for biometric synchronization.
     * - status: Account lifecycle state (e.g., Active, Inactive).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'department_id',
        'device_user_id',
        'status',
    ];

    /**
     * PHASE 2: SECURITY & DATA TRANSFORMATION
     * OBJECTIVE: Protect sensitive credentials and automate data type casting.
     * HIDDEN: Ensures passwords and remember tokens are excluded from array/JSON serializations.
     * CASTS: Automates the conversion of 'email_verified_at' to a Carbon date object and ensures the 'password' is treated as a hashed string.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * PHASE 3: ADMINISTRATIVE & STRUCTURAL RELATIONSHIPS
     * OBJECTIVE: Connect the user to the organizational hierarchy for access control and reporting.
     * ROLE: Inverse One-to-Many linking the user to their permission set.
     * DEPARTMENT: Inverse One-to-Many linking the user to their specific functional unit.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * PHASE 4: TRANSACTIONAL & ANALYTICAL HISTORY
     * OBJECTIVE: Link the user to their historical activity, attendance, and biometric events.
     * ATTENDANCES: Chronological log of daily shift records.
     * BIOMETRIC LOGS: Raw device interactions mapped via hardware UID.
     * MONTHLY REPORTS: High-level labor summaries and aggregate metrics.
     * AUDIT LOGS: Recorded system actions for forensic and accountability tracking.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function biometricLogs()
    {
        return $this->hasMany(BiometricLog::class, 'device_user_id', 'device_user_id');
    }

    public function monthlyReports()
    {
        return $this->hasMany(MonthlyReport::class);
    }
}