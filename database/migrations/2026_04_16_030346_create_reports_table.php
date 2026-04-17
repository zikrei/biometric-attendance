<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
       Schema::create('reports', function (Blueprint $table) {
           $table->id();

           // Foreign key reference to users
           $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

           // Report data
           $table->date('start_date');
           $table->date('end_date');
           $table->enum('status', ['generated', 'pending', 'approved'])->default('pending');
           $table->timestamps();
       });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
       Schema::dropIfExists('reports');
   }
}
