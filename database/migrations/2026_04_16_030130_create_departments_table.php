<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations to initialize the organizational department registry.
     */
    public function up(): void
    {
        /**
         * PHASE 1: TABLE INITIALIZATION & STRUCTURAL DEFINITION
         * OBJECTIVE: Establish the primary storage container for departmental entities.
         * CONSTRAINTS: 
         * - id: Auto-incrementing primary key for unique department identification.
         * - name: A string field defining the department's title.
         * - timestamps: Automated tracking of creation and modification events.
         */
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to roll back the departmental schema.
     */
    public function down(): void
    {
        /**
         * PHASE 1: DATABASE DECONSTRUCTION
         * OBJECTIVE: Safely remove the departments table from the database schema.
         * PROCEDURES: Executes a conditional drop to ensure system stability during rollbacks.
         */
        Schema::dropIfExists('departments');
    }
}