<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
   /**
    * Run the migrations to establish the role-based access control (RBAC) foundation.
    */
   public function up(): void
   {
       /**
        * PHASE 1: TABLE INITIALIZATION & SCHEMA DEFINITION
        * OBJECTIVE: Create the primary storage container for organizational roles.
        * CONSTRAINTS: 
        * - id: Auto-incrementing primary key for unique role identification.
        * - name: A unique string field to prevent duplicate role designations (e.g., Admin, Staff, HOD).
        * - timestamps: Automatic tracking of record creation and modification dates[cite: 23].
        */
       Schema::create('roles', function (Blueprint $table) {
           $table->id();
           $table->string('name')->unique();  
           $table->timestamps();
       });
   }

   /**
    * Reverse the migrations to roll back the database state.
    */
   public function down(): void
   {
       /**
        * PHASE 1: DATABASE DECONSTRUCTION
        * OBJECTIVE: Safely remove the roles table during system rollbacks[cite: 23].
        * PROCEDURES: Executes a drop-if-exists command to prevent errors during re-migration[cite: 23].
        */
       Schema::dropIfExists('roles');
   }
}