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

        // 1. Define the folder paths
        $dropzone = storage_path('app/biometrics_dropzone');
        $archive  = storage_path('app/biometrics_archive');

        // Make sure folders exist
        if (!File::exists($dropzone)) File::makeDirectory($dropzone, 0755, true);
        if (!File::exists($archive)) File::makeDirectory($archive, 0755, true);

        // 2. Get all files in the dropzone (supports .xlsx and .csv)
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

            // 1. Assign the reader to a variable so we can control it
            $reader = SimpleExcelReader::create($filePath);
            
            $reader->getRows()->each(function(array $row) use (&$insertedCount) {
                
                // TEMPORARY: This will print the exact column names to your terminal!
                // Once you know the exact names, you can delete this line.
                if ($insertedCount === 0) {
                    dump($row); 
                }

                // IMPORTANT: Change these to match what prints out in your terminal
                $userId = $row['EnrollNo'] ?? null;
                
                // We use try-catch here in case the date format is weird in Excel
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

            // 2. FORCE Windows to let go of the file lock!
            unset($reader);
            gc_collect_cycles(); // Forces PHP to clean up memory immediately

            $this->info("Successfully inserted {$insertedCount} logs from {$fileName}.");

            // 3. Now move the file
            $newFileName = time() . '_' . $fileName;
            File::move($filePath, $archive . '/' . $newFileName);
            
            $this->info("Moved {$fileName} to archive.");
        }

        $this->info('All Excel files processed!');
    }
}