<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncFingerTec extends Command
{
    // The command to run in the terminal
    protected $signature = 'sync:fingertec';
    protected $description = 'Pulls raw attendance logs from the FingerTec biometric device via COM';

    public function handle()
    {
        $this->info('Starting FingerTec Sync via ActiveX/COM...');

        // 1. Initialize the COM Object
        try {
            // This is the "magic" line that connects PHP to the BioBridgeSDKv3.ocx
            $sdk = new \COM("BioBridgeSDKv3.BioBridgeSDK");
        } catch (\Exception $e) {
            $this->error("Failed to load COM Object. Did you register the OCX and enable com_dotnet in php.ini?");
            $this->error($e->getMessage());
            return;
        }

        // 2. Connect to the Device via TCP/IP
        $deviceModel = "AC100"; // Change this to your exact model (e.g., "AC100+", "TA100")
        $deviceNo = 1;
        $ipAddress = "192.168.1.201"; // Change to your device's actual IP
        $port = 4370;
        $commKey = 0;

        $this->info("Attempting to connect to {$deviceModel} at {$ipAddress}...");
        
        // Calling the Connect_TCPIP method from the SDK [cite: 415, 433]
        $isConnected = $sdk->Connect_TCPIP($deviceModel, $deviceNo, $ipAddress, $port, $commKey);

        if ($isConnected !== 0) {
            $this->error("Connection failed! Please check the IP address and make sure the device is on.");
            return;
        }

        $this->info("Connected successfully! Downloading logs...");

        // 3. Read Logs into Device Memory
        $logCount = new \VARIANT();
        // Calling ReadGeneralLog method [cite: 1107]
        $readResult = $sdk->ReadGeneralLog($logCount);

        if ($readResult !== 0) {
            $this->error("Failed to read logs from device memory.");
            $sdk->Disconnect(); // Always clean up [cite: 485]
            return;
        }

        $this->info("Found " . (int)$logCount . " total logs on the device.");

        // 4. Retrieve Logs one by one
        $enrollNo = new \VARIANT();
        $year = new \VARIANT();
        $month = new \VARIANT();
        $day = new \VARIANT();
        $hour = new \VARIANT();
        $minute = new \VARIANT();
        $second = new \VARIANT();
        $verifyMode = new \VARIANT();
        $inOutMode = new \VARIANT();
        $workCode = new \VARIANT();

        $insertedCount = 0;

        // Loop through memory buffer using GetGeneralLog [cite: 1123, 1151]
        while ($sdk->GetGeneralLog($enrollNo, $year, $month, $day, $hour, $minute, $second, $verifyMode, $inOutMode, $workCode) == 0) {
            
            // Format the timestamp (YYYY-MM-DD HH:MM:SS)
            $timestamp = sprintf("%04d-%02d-%02d %02d:%02d:%02d", 
                (int)$year, (int)$month, (int)$day, (int)$hour, (int)$minute, (int)$second
            );

            // 5. Insert into your database staging table
            DB::table('biometric_logs')->insertOrIgnore([
                'device_user_id' => (string)$enrollNo,
                'punch_time'     => $timestamp,
                'punch_state'    => (int)$inOutMode,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $insertedCount++;
        }

        // 6. Disconnect
        $sdk->Disconnect(); [cite: 485]
        $this->info("Sync Complete! Saved {$insertedCount} logs to the database.");
    }
}