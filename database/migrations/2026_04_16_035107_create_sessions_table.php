<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations to establish the database-backed session storage schema.
     */
    public function up(): void
    {
        /**
         * PHASE 1: SESSION IDENTIFICATION & PAYLOAD STORAGE
         * OBJECTIVE: Create a high-performance container for active user session data.
         * ATTRIBUTES:
         * - id: A unique primary string identifier for the session.
         * - payload: A long text field containing serialized session data.
         * - last_activity: An integer timestamp used for session expiration and garbage collection.
         */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            /**
             * PHASE 2: FORENSIC & SECURITY METADATA
             * OBJECTIVE: Record connection metadata to prevent session hijacking and provide auditability.
             * ATTRIBUTES:
             * - user_id: Foreign key to the 'users' table, allowing sessions to be invalidated by user.
             * - ip_address: Records the client's network address.
             * - user_agent: Captures browser and OS metadata.
             */
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations to roll back the session storage schema.
     */
    public function down(): void
    {
        /**
         * PHASE 1: SCHEMA DECONSTRUCTION
         * OBJECTIVE: Safely remove the 'sessions' table during a database rollback.
         */
        Schema::dropIfExists('sessions');
    }
}