<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations to establish the central user registry and authentication schema.
     */
    public function up(): void
    {
        /**
         * PHASE 1: CORE IDENTITY & AUTHENTICATION
         * OBJECTIVE: Define primary identification and credential storage for system users.
         * ATTRIBUTES:
         * - id: Auto-incrementing primary key.
         * - name: Full legal name of the employee.
         * - email: Unique login identifier with a verification timestamp for security.
         * - password: Encrypted string for authentication.
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            /**
             * PHASE 2: HARDWARE INTEGRATION & ORGANIZATIONAL MAPPING
             * OBJECTIVE: Bridge the digital profile with physical biometric hardware and organizational units.
             * ATTRIBUTES:
             * - device_user_id: Nullable string used to map user records to external biometric device logs.
             * - role_id: Unsigned big integer for permission tiering.
             * - department_id: Unsigned big integer for departmental grouping.
             * - status: Defaults to 'Active' to manage account lifecycle access.
             */
            $table->string('device_user_id')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('department_id');
            $table->string('status')->default('Active');

            /**
             * PHASE 3: SESSION MANAGEMENT & AUDIT TRACKING
             * OBJECTIVE: Maintain persistent session data and record historical changes.
             * ATTRIBUTES:
             * - remember_token: Supports "Remember Me" authentication functionality.
             * - timestamps: Standard Laravel tracking for record creation and modification.
             */
            $table->rememberToken();
            $table->timestamps();

            /**
             * PHASE 4: RELATIONAL INTEGRITY ENFORCEMENT
             * OBJECTIVE: Establish foreign key constraints to ensure database referential integrity.
             * CONSTRAINTS:
             * - role_id: References the 'roles' table.
             * - department_id: References the 'departments' table.
             */
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations to roll back the user schema.
     */
    public function down(): void
    {
        /**
         * PHASE 1: SCHEMA DECONSTRUCTION
         * OBJECTIVE: Safely remove the users table during a database rollback.
         */
        Schema::dropIfExists('users');
    }
}