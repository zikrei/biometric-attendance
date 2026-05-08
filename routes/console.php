<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; 

/**
 * PHASE 1: INTERACTIVE COMMAND DEFINITIONS
 * OBJECTIVE: Provide CLI-based utility functions for system administrators.
 * PROCEDURES: Executes the 'inspire' command to output motivational metadata to the console.
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * PHASE 2: HARDWARE-LEVEL DATA INGESTION
 * OBJECTIVE: Regularly poll physical biometric hardware for new raw punch events.
 * PROCEDURES: Triggers the 'sync:fingertec' command every five minutes to retrieve data from the terminal.
 */
Schedule::command('sync:fingertec')->everyFiveMinutes();

/**
 * PHASE 3: RELATIONAL DATA TRANSFORMATION
 * OBJECTIVE: Convert raw biometric logs into finalized, actionable attendance records.
 * PROCEDURES: 
 * - Executes 'sync:attendances' immediately following the hardware poll interval.
 * - Logic: Processes logs where 'is_processed' is false to populate the 'attendances' table.
 */
Schedule::command('sync:attendances')->everyFiveMinutes();

/**
 * PHASE 4: FILE-BASED INTEGRITY & LEGACY INTEGRATION
 * OBJECTIVE: Support secondary data entry methods via automated file system monitoring.
 * PROCEDURES: Monitors and ingests Excel files dropped by external machines into the primary log registry every five minutes.
 */
Schedule::command('sync:excel')->everyFiveMinutes();