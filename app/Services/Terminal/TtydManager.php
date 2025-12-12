<?php

namespace App\Services\Terminal;

use App\Models\Server;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Manages ttyd processes for SSH terminal sessions
 *
 * ttyd is a web-based terminal emulator that provides a full-featured
 * terminal experience over WebSocket. This manager starts ttyd instances
 * that connect to SSH servers.
 */
class TtydManager
{
    private const BASE_PORT = 7700;

    private const MAX_SESSIONS = 100;

    /**
     * Get xterm.js theme configuration for a given theme name
     */
    private function getTerminalTheme(string $theme): array
    {
        $themes = [
            'saturn' => [
                'background' => '#f7f3ed',
                'foreground' => '#2d3748',
                'cursor' => '#e67e22',
                'cursorAccent' => '#f7f3ed',
                'selectionBackground' => 'rgba(230, 126, 34, 0.3)',
            ],
            'dracula' => [
                'background' => '#282a36',
                'foreground' => '#f8f8f2',
                'cursor' => '#ff79c6',
                'cursorAccent' => '#282a36',
                'selectionBackground' => '#44475a',
                'black' => '#21222c',
                'red' => '#ff5555',
                'green' => '#50fa7b',
                'yellow' => '#f1fa8c',
                'blue' => '#bd93f9',
                'magenta' => '#ff79c6',
                'cyan' => '#8be9fd',
                'white' => '#f8f8f2',
            ],
            'github-dark' => [
                'background' => '#0d1117',
                'foreground' => '#c9d1d9',
                'cursor' => '#58a6ff',
                'cursorAccent' => '#0d1117',
                'selectionBackground' => '#1f6feb',
                'black' => '#484f58',
                'red' => '#ff7b72',
                'green' => '#3fb950',
                'yellow' => '#d29922',
                'blue' => '#58a6ff',
                'magenta' => '#bc8cff',
                'cyan' => '#39c5cf',
                'white' => '#b1bac4',
            ],
            'github-light' => [
                'background' => '#ffffff',
                'foreground' => '#24292f',
                'cursor' => '#0969da',
                'cursorAccent' => '#ffffff',
                'selectionBackground' => 'rgba(9, 105, 218, 0.3)',
                'black' => '#24292f',
                'red' => '#cf222e',
                'green' => '#1a7f37',
                'yellow' => '#9a6700',
                'blue' => '#0969da',
                'magenta' => '#8250df',
                'cyan' => '#1b7c83',
                'white' => '#6e7781',
            ],
            'cyberpunk' => [
                'background' => '#050505',
                'foreground' => '#00ff9f',
                'cursor' => '#00ff9f',
                'cursorAccent' => '#050505',
                'selectionBackground' => 'rgba(214, 0, 255, 0.3)',
                'black' => '#121212',
                'red' => '#ff0055',
                'green' => '#00ff9f',
                'yellow' => '#fcee0a',
                'blue' => '#00f0ff',
                'magenta' => '#d600ff',
                'cyan' => '#00f0ff',
                'white' => '#ffffff',
            ],
        ];

        return $themes[$theme] ?? $themes['saturn'];
    }

    /**
     * Start a new ttyd session for an SSH connection
     */
    public function startSession(Server $server, ?string $theme = null): array
    {
        try {
            $sessionId = 'ttyd_'.Str::uuid();
            $port = $this->findAvailablePort();
            $theme = $theme ?? 'saturn';

            if (! $port) {
                return [
                    'success' => false,
                    'error' => 'No available ports for terminal session',
                ];
            }

            // Kill any existing process on this port
            $this->killProcessOnPort($port);

            // Build the SSH command arguments
            $commandArgs = $this->buildSshCommand($server);

            // Get theme configuration
            $themeConfig = $this->getTerminalTheme($theme);
            $themeJson = json_encode($themeConfig);

            // Use the start-ttyd.sh script to start ttyd completely detached
            $scriptPath = base_path('start-ttyd.sh');

            if (! file_exists($scriptPath)) {
                throw new \Exception('start-ttyd.sh script not found');
            }

            // Build the command: start-ttyd.sh <port> <theme_json> <ssh_command_args...>
            $shellCommand = sprintf(
                '/bin/bash %s %d %s %s 2>&1',
                escapeshellarg($scriptPath),
                $port,
                escapeshellarg($themeJson),
                implode(' ', array_map('escapeshellarg', $commandArgs))
            );

            Log::info('Starting ttyd via script', [
                'port' => $port,
                'script_command' => $shellCommand,
                'server' => $server->name,
                'ssh_args' => $commandArgs,
            ]);

            // Execute the script
            $output = shell_exec($shellCommand);
            $output = trim($output ?? '');

            Log::debug('Script output', ['output' => $output]);

            // Parse the output
            $pid = 0;
            if (strpos($output, 'SUCCESS:') === 0) {
                $pid = (int) substr($output, 8);
            } else {
                Log::error('Script failed', ['output' => $output]);

                return [
                    'success' => false,
                    'error' => 'Failed to start ttyd: '.$output,
                ];
            }

            // Verify the port is listening
            usleep(300000); // 300ms
            if (! $this->isPortListening($port)) {
                Log::warning('Port not listening after script, retrying...', ['port' => $port]);
                usleep(500000); // Wait another 500ms
                usleep(500000); // Wait another 500ms

                if (! $this->isPortListening($port)) {
                    return [
                        'success' => false,
                        'error' => 'ttyd failed to start - port not listening',
                    ];
                }
            }

            // Store session info in cache
            Cache::put("ttyd_session_{$sessionId}", [
                'server_id' => $server->id,
                'port' => $port,
                'pid' => $pid,
                'command' => $shellCommand,
                'created_at' => now(),
            ], now()->addHours(2));

            // Update server last connected
            $server->update(['last_connected_at' => now()]);

            Log::info('ttyd session started successfully', [
                'session_id' => $sessionId,
                'port' => $port,
                'pid' => $pid,
                'server' => $server->name,
            ]);

            return [
                'success' => true,
                'session_id' => $sessionId,
                'port' => $port,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to start ttyd session', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'server_id' => $server->id,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Start a background process and return its PID
     */
    private function startBackgroundProcess(string $command): int
    {
        // Use proc_open to start ttyd in a way that survives PHP request
        $descriptorspec = [
            0 => ['file', '/dev/null', 'r'],  // stdin
            1 => ['file', '/dev/null', 'w'],  // stdout
            2 => ['file', '/dev/null', 'w'],  // stderr
        ];

        // Start the process with nohup to survive parent exit
        $fullCommand = "nohup {$command}";

        Log::debug('Starting ttyd via proc_open', ['command' => $fullCommand]);

        $process = proc_open($fullCommand, $descriptorspec, $pipes);

        if (! is_resource($process)) {
            Log::error('Failed to start process via proc_open');

            return 0;
        }

        // Get the status
        $status = proc_get_status($process);
        $pid = $status['pid'] ?? 0;

        // Close the process handle but let the process continue running
        // Don't use proc_close as it waits for the process
        // Instead just let the handle go out of scope

        Log::debug('proc_open started', ['status' => $status]);

        // Wait a bit and find the actual ttyd PID (child of the shell)
        usleep(500000); // 500ms

        // Find ttyd PID since proc_open returns the shell PID
        $ttydPid = $this->findTtydPid();

        Log::debug('Background process result', ['shell_pid' => $pid, 'ttyd_pid' => $ttydPid]);

        return $ttydPid ?: $pid;
    }

    /**
     * Find the PID of a running ttyd process
     */
    private function findTtydPid(): int
    {
        $output = shell_exec('pgrep -n ttyd 2>/dev/null');

        return $output ? (int) trim($output) : 0;
    }

    /**
     * Check if a process is running
     */
    private function isProcessRunning(int $pid): bool
    {
        if ($pid <= 0) {
            return false;
        }
        $result = shell_exec("ps -p {$pid} -o pid= 2>/dev/null");

        return ! empty(trim($result ?? ''));
    }

    /**
     * Check if a port is listening
     */
    private function isPortListening(int $port): bool
    {
        // Use full path for lsof since PHP may have limited PATH
        $result = shell_exec("/usr/sbin/lsof -i :{$port} -P -n 2>/dev/null | /usr/bin/grep LISTEN");

        return ! empty(trim($result ?? ''));
    }

    /**
     * Kill any process on a specific port
     */
    private function killProcessOnPort(int $port): void
    {
        shell_exec("lsof -ti:{$port} | xargs kill -9 2>/dev/null");
        usleep(100000); // 100ms
    }

    /**
     * Build SSH command for the server - returns array of arguments for ttyd
     */
    private function buildSshCommand(Server $server): array
    {
        $host = $server->host;
        $port = (int) $server->port;
        $username = $server->username;

        // For development/testing - if localhost, use a local shell
        if ($server->host === 'localhost' || $server->host === '127.0.0.1') {
            Log::info('Using local shell for localhost connection');

            return ['/bin/bash'];
        }

        // Find sshpass path
        $sshpassPath = trim(shell_exec('which sshpass 2>/dev/null') ?: '');
        if (empty($sshpassPath)) {
            // Try common locations
            $commonPaths = ['/usr/bin/sshpass', '/usr/local/bin/sshpass', '/opt/homebrew/bin/sshpass', getenv('HOME').'/bin/sshpass'];
            foreach ($commonPaths as $path) {
                if (file_exists($path)) {
                    $sshpassPath = $path;
                    break;
                }
            }
        }

        if ($server->auth_type === 'key') {
            // Write private key to temporary file
            $keyFile = tempnam(sys_get_temp_dir(), 'ssh_key_');
            file_put_contents($keyFile, $server->private_key);
            chmod($keyFile, 0600);

            // Store key file path in cache for cleanup
            Cache::put("ttyd_keyfile_{$keyFile}", true, now()->addHours(2));

            return [
                '/usr/bin/ssh',
                '-i',
                $keyFile,
                '-p',
                (string) $port,
                '-o',
                'StrictHostKeyChecking=no',
                '-o',
                'UserKnownHostsFile=/dev/null',
                '-o',
                'ConnectTimeout=10',
                "{$username}@{$host}",
            ];
        }

        // For password auth, use sshpass
        $passFile = tempnam(sys_get_temp_dir(), 'ssh_pass_');
        file_put_contents($passFile, $server->password);
        chmod($passFile, 0600);

        // Store password file path in cache for cleanup
        Cache::put("ttyd_passfile_{$passFile}", true, now()->addHours(2));

        Log::info('Using sshpass for password auth', [
            'sshpass_path' => $sshpassPath,
            'pass_file' => $passFile,
            'password_length' => strlen($server->password),
        ]);

        return [
            $sshpassPath,
            '-f',
            $passFile,
            '/usr/bin/ssh',
            '-p',
            (string) $port,
            '-o',
            'StrictHostKeyChecking=no',
            '-o',
            'UserKnownHostsFile=/dev/null',
            '-o',
            'ConnectTimeout=10',
            "{$username}@{$host}",
        ];
    }

    /**
     * Find an available port for ttyd
     */
    private function findAvailablePort(): ?int
    {
        for ($i = 0; $i < self::MAX_SESSIONS; $i++) {
            $port = self::BASE_PORT + $i;

            // Check if port is in use by checking cache
            $inUse = Cache::get("ttyd_port_{$port}");

            if (! $inUse) {
                // Reserve the port
                Cache::put("ttyd_port_{$port}", true, now()->addHours(2));

                return $port;
            }
        }

        return null;
    }

    /**
     * Stop a ttyd session
     */
    public function stopSession(string $sessionId): bool
    {
        try {
            $session = Cache::get("ttyd_session_{$sessionId}");

            if (! $session) {
                return false;
            }

            // Kill the ttyd process using shell command
            if (isset($session['pid']) && $session['pid'] > 0) {
                shell_exec("kill -9 {$session['pid']} 2>/dev/null");
            }

            // Also kill by port to be safe
            if (isset($session['port'])) {
                $this->killProcessOnPort($session['port']);
            }

            // Release the port
            Cache::forget("ttyd_port_{$session['port']}");

            // Clean up session
            Cache::forget("ttyd_session_{$sessionId}");

            Log::info('ttyd session stopped', [
                'session_id' => $sessionId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to stop ttyd session', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get session info
     */
    public function getSession(string $sessionId): ?array
    {
        return Cache::get("ttyd_session_{$sessionId}");
    }

    /**
     * Get WebSocket URL for a session
     */
    public function getWebSocketUrl(string $sessionId): ?string
    {
        $session = $this->getSession($sessionId);

        if (! $session) {
            return null;
        }

        return "ws://localhost:{$session['port']}";
    }
}
