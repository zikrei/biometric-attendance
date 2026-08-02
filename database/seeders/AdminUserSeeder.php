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
     * Execute the database seeding for initial administrative and role-based access.
     */
    public function run(): void
    {
        /**
         * PHASE 1: ORGANIZATIONAL DEPENDENCY RESOLUTION
         * OBJECTIVE: Identify the necessary role and department entities required for user placement.
         * PROCEDURES: 
         * - Locates the core roles to define system permissions.
         * - Identifies the specific departments for functional placement.
         */
        
        // Fetch Roles
        $adminRole     = Role::where('name', 'Admin')->first();
        $integrityRole = Role::where('name', 'Integrity')->first();
        $hodRole       = Role::where('name', 'HOD')->first();
        $staffRole     = Role::where('name', 'Staff')->first();

        // Fetch Departments
        $sysAdminDept  = Department::where('name', 'Systems Administration')->first();
        $integrityDept = Department::where('name', 'Integrity')->first();
        $itDept        = Department::where('name', 'IT')->first();

        /**
         * PHASE 2: SECURE IDEMPOTENT USER PROVISIONING
         * OBJECTIVE: Establish persistent credential sets for all roles while preventing record duplication.
         * PROCEDURES: 
         * - Employs the 'firstOrCreate' method mapped to unique emails.
         * - Maps users to their designated Roles and Departments.
         */

        // 1. Admin - Systems Administration
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'          => 'System Admin',
                'password'      => Hash::make('password123'),
                'role_id'       => $adminRole->id,
                'department_id' => $sysAdminDept->id,
                'status'        => 'Active',
            ]
        );

        // 2. Integrity Unit - Integrity
        User::firstOrCreate(
            ['email' => 'integrity@example.com'],
            [
                'name'          => 'Integrity User',
                'password'      => Hash::make('password123'),
                'role_id'       => $integrityRole->id,
                'department_id' => $integrityDept->id,
                'status'        => 'Active',
            ]
        );

        // 3. HOD - IT
        User::firstOrCreate(
            ['email' => 'hod@example.com'],
            [
                'name'          => 'IT HOD',
                'password'      => Hash::make('password123'),
                'role_id'       => $hodRole->id,
                'department_id' => $itDept->id,
                'status'        => 'Active',
            ]
        );

        // 4. Staff - IT
        User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name'          => 'IT Staff',
                'password'      => Hash::make('password123'),
                'role_id'       => $staffRole->id,
                'department_id' => $itDept->id,
                'status'        => 'Active',
            ]
        );
    }
}