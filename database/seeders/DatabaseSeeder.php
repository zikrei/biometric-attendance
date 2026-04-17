<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class, // 1st: Create Departments
            RoleSeeder::class,       // 2nd: Create Roles (Admin, HOD, etc.)
            AdminUserSeeder::class,  // 3rd: Create Users and assign them the roles/departments
        ]);
    }
}