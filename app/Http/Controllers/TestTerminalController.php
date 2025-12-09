<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class TestTerminalController extends Controller
{
    public function show()
    {
        return view('terminal.test');
    }

    public function start()
    {
        try {
            // Find an available port
            $port = $this->findAvailablePort();

            // Start ttyd with just bash (no SSH)
            $command = "ttyd --port {$port} --writable /bin/bash";

            // Start in background
            $output = [];
            exec("nohup {$command} > /dev/null 2>&1 & echo $!", $output);
            $pid = isset($output[0]) ? (int) $output[0] : 0;

            // Wait for ttyd to start
            usleep(500000); // 0.5 seconds

            // Verify process is running
            if ($pid > 0) {
                $check = trim(shell_exec("ps -p {$pid} | grep -v PID") ?: '');
                if (empty($check)) {
                    throw new \Exception('ttyd process failed to start');
                }
            }

            Log::info('Test terminal started', ['port' => $port, 'pid' => $pid]);

            return response()->json([
                'success' => true,
                'port' => $port,
                'pid' => $pid,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to start test terminal', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function findAvailablePort(): int
    {
        // Start from port 7800
        for ($i = 0; $i < 100; $i++) {
            $port = 7800 + $i;
            $connection = @fsockopen('localhost', $port);
            if (! $connection) {
                return $port;
            }
            fclose($connection);
        }
        throw new \Exception('No available ports');
    }
}
