<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to initialize the secondary table for attendance discrepancy management.
     */
    public function up()
    {
        /**
         * PHASE 1: TABLE INITIALIZATION & RELATIONAL INTEGRITY
         * OBJECTIVE: Establish a persistent link between a primary attendance record and its formal justification.
         * PROCEDURES:
         * - id: Primary key for unique record identification.
         * - attendance_id: Foreign key that enforces referential integrity with the 'attendances' table.
         * - Cascade Policy: Deletes the justification automatically if the parent attendance record is removed.
         */
        Schema::create('attendance_justifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');

            /**
             * PHASE 2: STATUS MANAGEMENT & STATE TRACKING
             * OBJECTIVE: Manage the administrative lifecycle of the discrepancy request.
             * ATTRIBUTES:
             * - status: Tracks the workflow state (e.g., pending, approved, rejected), defaulting to 'pending' for HOD review.
             */
            $table->string('status')->default('pending'); 

            /**
             * PHASE 3: DOCUMENTATION & EVIDENTIARY STORAGE
             * OBJECTIVE: Capture qualitative and binary evidence regarding the attendance discrepancy.
             * ATTRIBUTES:
             * - reason: Textual description explaining the absence or late punch.
             * - attachment: Nullable field storing the file path to supporting documentation (e.g., medical certificates).
             * - timestamps: Standard tracking for submission and last modification times.
             */
            $table->text('reason');
            $table->string('attachment')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to roll back the justification schema.
     */
    public function down()
    {
        /**
         * PHASE 1: SCHEMA DECONSTRUCTION
         * OBJECTIVE: Safely remove the 'attendance_justifications' table from the database state.
         */
        Schema::dropIfExists('attendance_justifications');
    }
};