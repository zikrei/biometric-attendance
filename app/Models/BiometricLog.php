<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricLog extends Model
{
    use HasFactory;

    /**
     * PHASE 1: DATA ACQUISITION & ATTRIBUTE MAPPING
     * OBJECTIVE: Define the schema for raw data ingested from physical biometric hardware.
     * ATTRIBUTES: 
     * - device_user_id: The unique identifier assigned to the employee on the physical device.
     * - punch_time: The raw timestamp recorded at the moment of interaction.
     * - punch_state: An integer or string representing the transaction type (e.g., Check-In, Check-Out).
     */
    protected $fillable = [
        'device_user_id',
        'punch_time',
        'punch_state',
    ];

    /**
     * PHASE 2: IDENTITY ASSOCIATION & RELATIONAL MAPPING
     * OBJECTIVE: Bridge the gap between hardware-level IDs and the internal system User accounts.
     * TYPE: Inverse One-to-Many.
     * PROCEDURES: 
     * - Establishes a link to the 'User' model using 'device_user_id' as the joining key for both tables.
     * - OUTCOME: Enables the system to identify which employee generated a specific raw biometric event.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'device_user_id', 'device_user_id');
    }
}