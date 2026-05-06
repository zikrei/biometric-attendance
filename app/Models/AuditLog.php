<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    /**
     * PHASE 1: DATA STRUCTURE & AUDIT ATTRIBUTES
     * OBJECTIVE: Define the schema mapping for system-wide activity tracking.
     * ATTRIBUTES: 
     * - user_id: Reference to the agent performing the action.
     * - action: Brief identifier of the event type.
     * - description: Detailed log entry regarding the transaction.
     * - ip_address: Network identifier of the client.
     * - user_agent: Browser or system metadata for session analysis.
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    /**
     * PHASE 2: AUDIT TRAIL RELATIONSHIP
     * OBJECTIVE: Establish accountability by linking logs to specific system users.
     * TYPE: Inverse One-to-Many (belongsTo).
     * OUTCOME: Allows administrators to retrieve the specific 'User' instance responsible for a recorded action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}