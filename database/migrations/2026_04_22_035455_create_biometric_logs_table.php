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
            $table->string('device_user_id')->index(); 
            $table->dateTime('punch_time');
            $table->string('punch_state')->nullable(); 
            $table->timestamps();
            $table->unique(['device_user_id', 'punch_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('biometric_logs');
    }
};