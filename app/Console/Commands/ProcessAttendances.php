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

        /**
         * PHASE 1: DATA ACQUISITION & SORTING
         * OBJECTIVE: Retrieve a collection of raw biometric entries that have not yet been synchronized.
         * PARAMETERS: Filtering by 'is_processed' status and sorting chronologically to ensure data integrity.
         */
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
            /**
             * PHASE 2: IDENTITY VERIFICATION & MAPPING
             * OBJECTIVE: Match the biometric 'device_user_id' with a registered employee record.
             * EXCEPTION HANDLING: If no match is found, the log is flagged as processed to prevent system loops, and the iteration is terminated.
             */
            $user = DB::table('users')->where('device_user_id', $log->device_user_id)->first();

            if (!$user) {
                DB::table('biometric_logs')->where('id', $log->id)->update(['is_processed' => true]);
                continue;
            }

            // Normalization of timestamp into distinct Date and Time objects
            $punchTime = Carbon::parse($log->punch_time);
            $date = $punchTime->toDateString();
            $time = $punchTime->toTimeString();

            /**
             * PHASE 3: ATTENDANCE LOGIC & RECORD EVALUATION
             * OBJECTIVE: Determine if the entry represents a shift commencement or a shift conclusion.
             * SCENARIO A (New Entry): Creates a new record if no entry exists for the user on this date (Clock-In).
             * SCENARIO B (Existing Entry): Updates the existing record's final activity field (Clock-Out).
             */
            $attendance = DB::table('attendances')
                ->where('user_id', $user->id)
                ->where('date', $date)
                ->first();

            if (!$attendance) {
                DB::table('attendances')->insert([
                    'user_id'   => $user->id,
                    'date'      => $date,
                    'clock_in'  => $time,
                    'clock_out' => null,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]);
            } else {
                DB::table('attendances')
                    ->where('id', $attendance->id)
                    ->update([
                        'clock_out'  => $time,
                        'updated_at' => now(),
                    ]);
            }

            /**
             * PHASE 4: TRANSACTION FINALIZATION
             * OBJECTIVE: Mark the source log as successfully synchronized to prevent duplicate processing.
             * METRIC: Increment the internal counter for the final execution summary.
             */
            DB::table('biometric_logs')->where('id', $log->id)->update(['is_processed' => true]);
            $processedCount++;
        }

        $this->info("Successfully processed {$processedCount} raw logs into the attendances table!");
    }
}