<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Execute the database seeding for initial administrative access.
     */
    public function run(): void
    {
        /**
         * PHASE 1: ORGANIZATIONAL DEPENDENCY RESOLUTION
         * OBJECTIVE: Identify the necessary role and department entities required for administrative placement.
         * PROCEDURES: 
         * - Locates the 'Admin' role to define high-level system permissions.
         * - Identifies the 'IT' department as the functional unit for the initial user.
         */
        $adminRole = Role::where('name', 'Admin')->first();
        $itDepartment = Department::where('name', 'IT')->first();

        /**
         * PHASE 2: SECURE IDEMPOTENT USER PROVISIONING
         * OBJECTIVE: Establish a persistent administrative credential set while preventing record duplication.
         * PROCEDURES: 
         * - Employs the 'firstOrCreate' method to ensure the admin user exists without triggering unique constraint errors on subsequent runs.
         * - Maps the user to the resolved 'Admin' role and 'IT' department IDs.
         * - Applies cryptographic hashing to the default password to ensure immediate system security.
         */
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
                'department_id' => $itDepartment->id,
                'status' => 'Active',
            ]
        );
    }
}