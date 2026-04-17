<?php

namespace Database\Seeders;

use App\Models\Role; // <-- Crucial: Imports the Role model
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // This will safely create your 4 roles in the 'roles' table
        foreach (['Admin', 'Staff', 'HOD', 'Integrity'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}