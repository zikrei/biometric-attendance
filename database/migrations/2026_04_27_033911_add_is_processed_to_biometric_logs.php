<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to update the biometric logs schema with processing status.
     */
    public function up(): void
    {
        /**
         * PHASE 1: SCHEMA ENHANCEMENT & STATUS TRACKING
         * OBJECTIVE: Implement a state-tracking mechanism for raw biometric data.
         * PROCEDURES:
         * - is_processed: Adds a boolean flag to indicate if a log has been converted into an attendance entry.
         * - DEFAULT: Sets initial state to 'false' to ensure all new logs are caught by the background synchronization engine.
         * - PLACEMENT: Positions the column after 'punch_state' for logical grouping within the database.
         */
        Schema::table('biometric_logs', function (Blueprint $table) {
            $table->boolean('is_processed')->default(false)->after('punch_state');
        });
    }

    /**
     * Reverse the migrations to remove processing status tracking.
     */
    public function down(): void
    {
        /**
         * PHASE 1: DATABASE RESTORATION
         * OBJECTIVE: Revert the 'biometric_logs' table to its original schema by removing the synchronization flag.
         */
        Schema::table('biometric_logs', function (Blueprint $table) {
            $table->dropColumn('is_processed');
        });
    }
};