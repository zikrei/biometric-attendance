<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
       Schema::create('users', function (Blueprint $table) {
           $table->id();

           // Foreign keys for roles and departments
           $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();  // Role reference
           $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete(); // Department reference

           $table->string('device_user_id')->nullable()->unique();  // Optional device link

           // Basic user info
           $table->string('name');
           $table->string('email')->unique();
           $table->timestamp('email_verified_at')->nullable();
           $table->string('password');
           $table->rememberToken();
           $table->timestamps();
       });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
       Schema::dropIfExists('users');
   }
}
