<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Execute the database seeding to initialize the organizational structure.
     */
    public function run(): void
    {
        /**
         * PHASE 1: ORGANIZATIONAL DATASET DEFINITION
         * OBJECTIVE: Define the standard functional units required for system operation.
         * DATASET: Includes core departments such as IT, HR, Finance, Production, and Logistics.
         */
        $departments = [
            'IT',
            'HR',
            'Finance',
            'Production',
            'Logistics',
        ];

        /**
         * PHASE 2: IDEMPOTENT RECORD PERSISTENCE
         * OBJECTIVE: Populate the 'departments' table while ensuring data consistency.
         * PROCEDURES: 
         * - Iterates through the defined dataset.
         * - Logic: Employs 'firstOrCreate' to verify if a department exists before attempting insertion.
         * - OUTCOME: Prevents primary key collisions and duplicate naming errors during repeated system setups.
         */
        foreach ($departments as $name) {
            Department::firstOrCreate(['name' => $name]);
        }
    }
}