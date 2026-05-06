<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * PHASE 1: ORGANIZATIONAL ENTITY DEFINITION
     * OBJECTIVE: Serve as the primary structural foundation for grouping employees within the application.
     * PROCEDURES: Maintains the registry of departmental names used for filtering attendance reports and access control.
     */

    /**
     * PHASE 2: ONE-TO-MANY RELATIONAL MAPPING
     * OBJECTIVE: Establish a collective link to all users assigned to this specific organizational unit.
     * TYPE: One-to-Many (hasMany).
     * OUTCOME: Enables the application to retrieve a collection of User records belonging to the department for HOD oversight and departmental reporting.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}