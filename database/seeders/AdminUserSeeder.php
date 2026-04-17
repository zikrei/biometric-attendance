<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <--- 1. Add this import at the top

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $department = Department::first();

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'role_id' => $adminRole?->id, 
                'department_id' => $department?->id,
                // 2. Explicitly hash the password here!
                'password' => Hash::make('password123'), 
            ]
        );
    }
}