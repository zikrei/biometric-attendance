<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; 

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pulls from the SDK machine
Schedule::command('sync:fingertec')->everyFiveMinutes();

// Reads the Excel files dropped by the other machine
Schedule::command('sync:excel')->everyFiveMinutes();