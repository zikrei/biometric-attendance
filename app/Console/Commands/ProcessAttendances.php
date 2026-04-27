<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessAttendances extends Command
{
    protected $signature = 'sync:attendances';
    protected $description = 'Process raw biometric logs into daily attendances';

    public function handle()
    {
        $this->info('Looking for unprocessed biometric logs...');

        // 1. Get all logs we haven't processed yet, ordered by oldest first
        $logs = DB::table('biometric_logs')
                  ->where('is_processed', false)
                  ->orderBy('punch_time', 'asc')
                  ->get();

        if ($logs->isEmpty()) {
            $this->info('No new logs to process. You are up to date!');
            return;
        }

        $processedCount = 0;

        foreach ($logs as $log) {
            // 2. Find the actual employee in your users table 
            // (Assumes your users table has a 'device_user_id' column)
            $user = DB::table('users')->where('device_user_id', $log->device_user_id)->first();

            if (!$user) {
                // If the user isn't in our system yet, mark as processed and skip
                DB::table('biometric_logs')->where('id', $log->id)->update(['is_processed' => true]);
                continue;
            }

            // Split the datetime into Date and Time
            $punchTime = Carbon::parse($log->punch_time);
            $date = $punchTime->toDateString();  // e.g., "2026-04-27"
            $time = $punchTime->toTimeString();  // e.g., "08:30:00"

            // 3. Look for an existing attendance record for this user on this specific date
            $attendance = DB::table('attendances')
                ->where('user_id', $user->id)
                ->where('date', $date)
                ->first();

            if (!$attendance) {
                // Scenario A: First punch of the day! Create the record.
                DB::table('attendances')->insert([
                    'user_id'   => $user->id,
                    'date'      => $date,
                    'clock_in'  => $time,    // Changed to clock_in
                    'clock_out' => null,     // Changed to clock_out
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]);
            } else {
                // Scenario B: They punched again! Update the clock_out time.
                DB::table('attendances')
                    ->where('id', $attendance->id)
                    ->update([
                        'clock_out'  => $time, // Changed to clock_out
                        'updated_at' => now(),
                    ]);
            }

            // 4. Mark the raw log as safely processed!
            DB::table('biometric_logs')->where('id', $log->id)->update(['is_processed' => true]);
            $processedCount++;
        }

        $this->info("Successfully processed {$processedCount} raw logs into the attendances table!");
    }
}