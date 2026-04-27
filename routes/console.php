<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; 

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 1. Pull from the machine every 5 minutes
Schedule::command('sync:fingertec')->everyFiveMinutes();

// 2. Process the logs into attendances right after!
Schedule::command('sync:attendances')->everyFiveMinutes();

// Reads the Excel files dropped by the other machine
Schedule::command('sync:excel')->everyFiveMinutes();