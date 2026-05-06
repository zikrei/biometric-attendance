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

        /**
         * PHASE 1: NETWORK CONFIGURATION
         * OBJECTIVE: Define the target hardware address and communication port.
         * PARAMETERS: 
         * - IP Address: 10.30.0.110 (Static Device IP)
         * - Port: 4370 (Standard UDP port for ZKTeco/FingerTec protocol)
         */
        $ipAddress = "10.30.0.110"; 
        $port = 4370;               

        $this->info("Connecting to {$ipAddress}:{$port}...");
        
        /**
         * PHASE 2: SOCKET INITIALIZATION & HANDSHAKE
         * OBJECTIVE: Establish a low-level network connection with the biometric hardware.
         * PROCEDURES:
         * - Instantiate the ZKTeco socket wrapper.
         * - Execute a connection handshake via UDP.
         * EXCEPTION HANDLING: Aborts execution if the device is unreachable or the port is blocked.
         */
        $zk = new ZKTeco($ipAddress, $port);
        
        if (!$zk->connect()) {
            $this->error("Connection failed! Make sure the device is powered on and connected to the network.");
            return;
        }

        $this->info("Connected successfully! Downloading logs...");
        
        /**
         * PHASE 3: REMOTE DATA RETRIEVAL
         * OBJECTIVE: Extract raw attendance records stored in the device's internal memory.
         * DATA STRUCTURE: Returns an array of log entries containing User ID, Timestamp, and Punch State.
         * VALIDATION: Verifies if new data exists before proceeding to the database layer.
         */
        $attendance = $zk->getAttendance();
        
        if (empty($attendance)) {
            $this->info("No logs found on the device.");
            $zk->disconnect();
            return;
        }

        $this->info("Found " . count($attendance) . " logs. Saving to database...");

        $insertedCount = 0;

        /**
         * PHASE 4: DATABASE SYNCHRONIZATION & DUPLICATE PREVENTION
         * OBJECTIVE: Transform raw device logs into persistent database records.
         * DATA MAPPING:
         * - device_user_id: Cast from device 'id'.
         * - punch_time: Direct mapping of device 'timestamp'.
         * - punch_state: Cast from device 'state'.
         * INTEGRITY GUARD: Uses 'insertOrIgnore' to skip records already present in the 'biometric_logs' table.
         */
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

        /**
         * PHASE 5: SESSION TERMINATION
         * OBJECTIVE: Explicitly close the network socket to free up the device's communication channel.
         * FINALIZATION: Output the total number of processed records to the console.
         */
        $zk->disconnect();
        $this->info("Sync Complete! Saved {$insertedCount} logs.");
    }
}