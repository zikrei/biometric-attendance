<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get the first department (created by your DepartmentSeeder)
        $department = Department::firstOrCreate(['name' => 'General Administration']);

        // 2. Fetch the Role IDs from the database
        $adminRole = Role::where('name', 'Admin')->first();
        $hodRole = Role::where('name', 'HOD')->first();
        $staffRole = Role::where('name', 'Staff')->first();
        $integrityRole = Role::where('name', 'Integrity')->first();

        // 3. Create the Admin User
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'role_id' => $adminRole?->id,
                'department_id' => $department->id,
                'password' => Hash::make('password123'),
            ]
        );

        // 4. Create the HOD User
        User::firstOrCreate(
            ['email' => 'hod@example.com'],
            [
                'name' => 'Head of Department',
                'role_id' => $hodRole?->id,
                'department_id' => $department->id,
                'password' => Hash::make('password123'),
            ]
        );

        // 5. Create the Staff User
        User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Regular Staff Member',
                'role_id' => $staffRole?->id,
                'department_id' => $department->id,
                'password' => Hash::make('password123'),
            ]
        );

        // 6. Create the Integrity Unit User
        User::firstOrCreate(
            ['email' => 'integrity@example.com'],
            [
                'name' => 'Integrity Officer',
                'role_id' => $integrityRole?->id,
                'department_id' => $department->id,
                'password' => Hash::make('password123'),
            ]
        );
    }
}