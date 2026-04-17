<?php

namespace Database\Seeders;

use App\Models\Department; // <-- You must add this line
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['IT', 'HR', 'Finance'] as $department) {
            Department::firstOrCreate(['name' => $department]);
        }
    }
}