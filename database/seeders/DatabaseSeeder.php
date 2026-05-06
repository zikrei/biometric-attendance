<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Orchestrate the master seeding sequence for the application database.
     */
    public function run(): void
    {
        /**
         * PHASE 1: FOUNDATIONAL DEPENDENCY INITIALIZATION
         * OBJECTIVE: Populate the master registries that other system entities rely upon.
         * PROCEDURES: 
         * - Executes the 'RoleSeeder' and 'DepartmentSeeder' first.
         * - RATIONALE: User records cannot be instantiated without existing Role and Department IDs due to foreign key constraints.
         */
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
        ]);

        /**
         * PHASE 2: ADMINISTRATIVE & AUTHENTICATION PROVISIONING
         * OBJECTIVE: Establish the initial administrative access layer.
         * PROCEDURES: 
         * - Triggers the 'AdminUserSeeder' only after foundational dependencies are resolved.
         * - OUTCOME: Finalizes the environment for immediate deployment and testing.
         */
        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}