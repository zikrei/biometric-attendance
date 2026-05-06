<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to establish the raw biometric data ingestion schema.
     */
    public function up()
    {
        /**
         * PHASE 1: TABLE INITIALIZATION & SCHEMA DEFINITION
         * OBJECTIVE: Establish a high-frequency storage container for raw biometric punch data.
         * ATTRIBUTES: 
         * - id: Auto-incrementing primary key for unique record tracking.
         * - punch_state: A nullable string capturing the specific transaction type (e.g., Check-In, Break, Overtime).
         * - timestamps: Standard Laravel tracking for system-level data entry events[cite: 29].
         */
        Schema::create('biometric_logs', function (Blueprint $table) {
            $table->id();

            /**
             * PHASE 2: DATA INTEGRITY & HARDWARE MAPPING
             * OBJECTIVE: Ensure accurate mapping between physical devices and software records while preventing data collisions[cite: 29].
             * PROCEDURES:
             * - device_user_id: An indexed string for rapid retrieval of logs belonging to a specific hardware UID[cite: 29].
             * - Unique Constraint: Enforces a strict rule preventing duplicate log entries for the same user at the exact same second[cite: 29]. 
             *   This is critical for ensuring idempotency during repeated sync cycles[cite: 29].
             */
            $table->string('device_user_id')->index(); 

            /**
             * PHASE 3: TEMPORAL TRACKING
             * OBJECTIVE: Maintain a chronological record of hardware interaction events[cite: 29].
             * PROCEDURES: 
             * - punch_time: Captures the exact date and time the biometric event occurred on the physical device[cite: 29].
             */
            $table->dateTime('punch_time');
            $table->string('punch_state')->nullable(); 
            $table->timestamps();

            $table->unique(['device_user_id', 'punch_time']);
        });
    }

    /**
     * Reverse the migrations to roll back the raw log storage schema.
     */
    public function down()
    {
        /**
         * PHASE 1: DATABASE DECONSTRUCTION
         * OBJECTIVE: Safely remove the raw log storage container during system rollbacks[cite: 29].
         */
        Schema::dropIfExists('biometric_logs');
    }
};