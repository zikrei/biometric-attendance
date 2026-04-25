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

            // 3. Read the Excel file row by row
            SimpleExcelReader::create($filePath)->getRows()->each(function(array $row) use (&$insertedCount) {
                
                // IMPORTANT: Change 'EnrollNo', 'Time', and 'State' to the exact column names in your Excel file
                $userId = $row['EnrollNo'] ?? null;
                $time   = clone Carbon::parse($row['Time']); // Format to Y-m-d H:i:s
                $state  = $row['State'] ?? 0;

                if ($userId && $time) {
                    // 4. Insert into the staging table (Thanks to our unique constraint, duplicates are ignored!)
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

            $this->info("Successfully inserted {$insertedCount} logs from {$fileName}.");

            // 5. Move the file to the archive so it is not processed again
            // We append the current timestamp to the filename to prevent overwriting older archives
            $newFileName = time() . '_' . $fileName;
            File::move($filePath, $archive . '/' . $newFileName);
            
            $this->info("Moved {$fileName} to archive.");
        }

        $this->info('All Excel files processed!');
    }
}