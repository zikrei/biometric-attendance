<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * PHASE 1: AUTHORIZATION IDENTITY DEFINITION
     * OBJECTIVE: Serve as the primary classification registry for system access control.
     * PROCEDURES: Maintains the master list of user designations (e.g., Admin, HOD, Staff, Integrity) that dictate functional permissions across the platform.
     */

    /**
     * PHASE 2: ONE-TO-MANY RELATIONAL HIERARCHY
     * OBJECTIVE: Establish a direct link between a specific access level and the collection of users assigned to it.
     * TYPE: One-to-Many (hasMany).
     * OUTCOME: Enables the application to execute role-based middleware checks and departmental filtering by identifying all users associated with this classification.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}