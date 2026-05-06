<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;

class ProcessBiometricExcel extends Command
{
    protected $signature = 'sync:excel';
    protected $description = 'Reads biometric Excel files and moves them to the archive';

    public function handle()
    {
        $this->info('Looking for Excel files in the Dropzone...');

        /**
         * PHASE 1: DIRECTORY INITIALIZATION
         * OBJECTIVE: Define and validate the existence of required system paths.
         * PROCEDURES: 
         * - Define 'dropzone' for incoming files and 'archive' for processed records.
         * - Verify directory existence; auto-generate missing folders with 0755 permissions.
         */
        $dropzone = storage_path('app/biometrics_dropzone');
        $archive  = storage_path('app/biometrics_archive');

        if (!File::exists($dropzone)) File::makeDirectory($dropzone, 0755, true);
        if (!File::exists($archive)) File::makeDirectory($archive, 0755, true);

        /**
         * PHASE 2: FILE DISCOVERY & VALIDATION
         * OBJECTIVE: Identify all pending Excel or CSV datasets within the dropzone.
         * CRITERIA: Supports .xlsx and .csv formats. Terminates execution if the directory is empty.
         */
        $files = File::files($dropzone);

        if (empty($files)) {
            $this->info('No files found. Exiting.');
            return;
        }

        foreach ($files as $file) {
            $filePath = $file->getPathname();
            $fileName = $file->getFilename();
            $this->info("Processing file: {$fileName}");

            $insertedCount = 0;

            /**
             * PHASE 3: DATA EXTRACTION & DATABASE SYNCHRONIZATION
             * OBJECTIVE: Iterate through file rows and map data to the biometric_logs table.
             * MAPPING LOGIC:
             * - UID: Extracted from 'EnrollNo'.
             * - Timestamp: Parsed from 'Time' via Carbon with Exception handling for malformed dates.
             * - State: Extracted from 'State' (defaulting to 0).
             * TRANSACTION: Uses 'insertOrIgnore' to prevent duplicate entries from the same file.
             */
            $reader = SimpleExcelReader::create($filePath);
            
            $reader->getRows()->each(function(array $row) use (&$insertedCount) {
                
                // DATA DEBUGGING: Outputs the first row structure to the terminal for schema verification.
                if ($insertedCount === 0) {
                    dump($row); 
                }

                $userId = $row['EnrollNo'] ?? null;
                
                try {
                    $time = isset($row['Time']) ? Carbon::parse($row['Time']) : null;
                } catch (\Exception $e) {
                    $time = null;
                }

                $state  = $row['State'] ?? 0;

                if ($userId && $time) {
                    DB::table('biometric_logs')->insertOrIgnore([
                        'device_user_id' => $userId,
                        'punch_time'     => $time,
                        'punch_state'    => $state,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                    $insertedCount++;
                }
            });

            /**
             * PHASE 4: RESOURCE OPTIMIZATION & MEMORY MANAGEMENT
             * OBJECTIVE: Release file locks and clear memory buffers to prevent Windows file-sharing violations.
             * METHOD: Explicitly unset the reader and trigger a Garbage Collection cycle (gc_collect_cycles).
             */
            unset($reader);
            gc_collect_cycles(); 

            $this->info("Successfully inserted {$insertedCount} logs from {$fileName}.");

            /**
             * PHASE 5: FILE ARCHIVAL & FINALIZATION
             * OBJECTIVE: Relocate the processed file to the archive to prevent re-processing.
             * NAMING CONVENTION: Appends a Unix timestamp to the filename to ensure uniqueness in storage.
             */
            $newFileName = time() . '_' . $fileName;
            File::move($filePath, $archive . '/' . $newFileName);
            
            $this->info("Moved {$fileName} to archive.");
        }

        $this->info('All Excel files processed!');
    }
}