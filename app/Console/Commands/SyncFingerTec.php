<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Rats\Zkteco\Lib\ZKTeco;

class SyncFingerTec extends Command
{
    protected $signature = 'sync:fingertec';
    protected $description = 'Pulls raw attendance logs directly via Network Sockets (Bypassing Windows COM)';

    public function handle()
    {
        $this->info('Starting pure PHP Socket Connection...');

        $ipAddress = "10.30.0.110"; // Your device IP
        $port = 4370;               // Default UDP port for all FingerTec/ZKTeco devices

        $this->info("Connecting to {$ipAddress}:{$port}...");
        
        // 1. Initialize the pure PHP Library
        $zk = new ZKTeco($ipAddress, $port);
        
        // 2. Connect via UDP network socket
        if (!$zk->connect()) {
            $this->error("Connection failed! Make sure the device is powered on and connected to the network.");
            return;
        }

        $this->info("Connected successfully! Downloading logs...");
        
        // 3. Download the logs directly from the device's network port
        $attendance = $zk->getAttendance();
        
        if (empty($attendance)) {
            $this->info("No logs found on the device.");
            $zk->disconnect();
            return;
        }

        $this->info("Found " . count($attendance) . " logs. Saving to database...");

        $insertedCount = 0;

        // 4. Loop through the logs and insert them
        foreach ($attendance as $log) {
            DB::table('biometric_logs')->insertOrIgnore([
                'device_user_id' => (string) $log['id'],
                'punch_time'     => $log['timestamp'],
                'punch_state'    => (string) $log['state'],
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $insertedCount++;
        }

        // 5. Clean up network connection
        $zk->disconnect();
        $this->info("Sync Complete! Saved {$insertedCount} logs.");
    }
}