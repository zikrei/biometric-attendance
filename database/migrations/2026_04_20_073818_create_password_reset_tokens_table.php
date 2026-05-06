<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to initialize the security token registry.
     */
    public function up(): void
    {
        /**
         * PHASE 1: SECURITY TOKEN INFRASTRUCTURE
         * OBJECTIVE: Establish a secure, temporary storage for password recovery tokens.
         * ATTRIBUTES:
         * - email: Acts as the primary identifier to link the token to a specific user account.
         * - token: A unique string used to verify the authenticity of the reset request.
         * - created_at: Timestamp used to calculate token expiration and enforce security windows.
         */
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations to roll back the security token schema.
     */
    public function down(): void
    {
        /**
         * PHASE 1: SCHEMA DECONSTRUCTION
         * OBJECTIVE: Safely remove the token registry during a database rollback.
         */
        Schema::dropIfExists('password_reset_tokens');
    }
};