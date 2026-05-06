<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations to initialize the primary attendance tracking schema.
     */
    public function up(): void
    {
        /**
         * PHASE 1: TABLE INITIALIZATION & RELATIONAL LINKING
         * OBJECTIVE: Create a persistent store for daily employee attendance records.
         * ATTRIBUTES:
         * - id: Unique primary identifier for the attendance event.
         * - user_id: Unsigned big integer serving as the foreign key to the 'users' table.
         */
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            /**
             * PHASE 2: TEMPORAL DATA CAPTURE
             * OBJECTIVE: Record the specific calendar date and transition timestamps for a work shift.
             * ATTRIBUTES:
             * - date: The targeted workday for the record.
             * - clock_in: Nullable time field for shift commencement.
             * - clock_out: Nullable time field for shift conclusion.
             */
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();

            /**
             * PHASE 3: AUDIT METADATA & INTEGRITY CONSTRAINTS
             * OBJECTIVE: Ensure referential integrity and provide automated auditing timestamps.
             * PROCEDURES:
             * - timestamps: Records creation and last update events.
             * - foreign: Establishes a formal database constraint linking the record to a valid user.
             */
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations to roll back the attendance schema.
     */
    public function down(): void
    {
        /**
         * PHASE 1: SCHEMA DECONSTRUCTION
         * OBJECTIVE: Safely remove the 'attendances' table from the database state.
         */
        Schema::dropIfExists('attendances');
    }
}