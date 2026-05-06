<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Execute the database seeding to initialize the system's authorization framework.
     */
    public function run(): void
    {
        /**
         * PHASE 1: AUTHORIZATION DATASET DEFINITION
         * OBJECTIVE: Define the essential permission tiers required for system-wide access control.
         * DATASET: Establishes the core classifications—'Admin', 'HOD', 'Staff', and 'Integrity'—which serve as the foundation for the RoleMiddleware.
         */
        $roles = ['Admin', 'HOD', 'Staff', 'Integrity'];

        /**
         * PHASE 2: IDEMPOTENT PERMISSION PERSISTENCE
         * OBJECTIVE: Initialize the roles table while maintaining structural integrity across multiple deployments.
         * PROCEDURES: 
         * - Iterates through the predefined role array.
         * - Logic: Utilizes the 'firstOrCreate' method to prevent duplicate role definitions and primary key conflicts.
         * - OUTCOME: Ensures a consistent authorization baseline that remains resilient during repeated system migrations.
         */
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}