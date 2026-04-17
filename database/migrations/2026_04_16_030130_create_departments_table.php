<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
       Schema::create('departments', function (Blueprint $table) {
           $table->id();
           $table->string('name')->unique();  // Department name (HR, IT, etc.)
           $table->timestamps();
       });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
       Schema::dropIfExists('departments');
   }
}
