<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('biometric_logs', function (Blueprint $table) {
            $table->id();
            // Storing as a string in case device IDs have leading zeros (e.g., "0014")
            $table->string('device_user_id')->index(); 
            $table->dateTime('punch_time');
            $table->string('punch_state')->nullable(); // Optional: e.g., "Check-In" or "Check-Out" if the device sends it
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('biometric_logs');
    }
};