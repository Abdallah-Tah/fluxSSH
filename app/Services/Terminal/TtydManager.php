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
     * Start a new ttyd session for an SSH connection
     */
    public function startSession(Server $server): array
    {
        try {
            $sessionId = 'ttyd_'.Str::uuid();
            $port = $this->findAvailablePort();

            if (! $port) {
                return [
                    'success' => false,
                    'error' => 'No available ports for terminal session',
                ];
            }

            // Build SSH command
            $sshCommand = $this->buildSshCommand($server);

            // Build simplified ttyd command - complex options were breaking shell execution
            $ttydCommand = sprintf(
                'ttyd --port %d --writable %s',
                $port,
                $sshCommand
            );

            // Start ttyd process in background using exec() for better error handling
            $output = [];
            $returnCode = 0;
            exec($ttydCommand.' > /dev/null 2>&1 & echo $!', $output, $returnCode);

            // The PID should be in the output
            $pid = isset($output[0]) ? (int) $output[0] : 0;

            // Log the command execution for debugging
            Log::info('ttyd command executed', [
                'command' => $ttydCommand,
                'return_code' => $returnCode,
                'pid' => $pid,
                'output' => $output,
            ]);

            // Wait a moment for ttyd to start
            usleep(500000); // 500ms

            // Verify the process is actually running
            if ($pid > 0) {
                $checkCommand = "ps -p {$pid} | grep -v PID";
                $processCheck = trim(shell_exec($checkCommand) ?: '');
                if (empty($processCheck)) {
                    Log::error('ttyd process died immediately after starting', [
                        'pid' => $pid,
                        'command' => $ttydCommand,
                    ]);
                    $pid = 0;
                }
            }

            // Store session info in cache
            Cache::put("ttyd_session_{$sessionId}", [
                'server_id' => $server->id,
                'port' => $port,
                'pid' => $pid,
                'created_at' => now(),
            ], now()->addHours(2));

            // Update server last connected
            $server->update(['last_connected_at' => now()]);

            Log::info('ttyd session started', [
                'session_id' => $sessionId,
                'port' => $port,
                'server' => $server->name,
            ]);

            return [
                'success' => true,
                'session_id' => $sessionId,
                'port' => $port,
                'url' => url("terminal/ws/{$sessionId}"),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to start ttyd session', [
                'error' => $e->getMessage(),
                'server_id' => $server->id,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build SSH command for the server
     */
    private function buildSshCommand(Server $server): string
    {
        $host = escapeshellarg($server->host);
        $port = $server->port;
        $username = escapeshellarg($server->username);

        if ($server->auth_type === 'key') {
            // Write private key to temporary file
            $keyFile = tempnam(sys_get_temp_dir(), 'ssh_key_');
            file_put_contents($keyFile, $server->private_key);
            chmod($keyFile, 0600);

            // Store key file path in cache for cleanup
            Cache::put("ttyd_keyfile_{$keyFile}", true, now()->addHours(2));

            return sprintf(
                'ssh -i %s -p %d -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null %s@%s',
                escapeshellarg($keyFile),
                $port,
                $username,
                $host
            );
        }

        // For password auth, write password to temp file to avoid shell escaping issues
        $passFile = tempnam(sys_get_temp_dir(), 'ssh_pass_');
        file_put_contents($passFile, $server->password);
        chmod($passFile, 0600);

        // Store password file path in cache for cleanup
        Cache::put("ttyd_passfile_{$passFile}", true, now()->addHours(2));

        return sprintf(
            'sshpass -f %s ssh -p %d -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null %s@%s',
            escapeshellarg($passFile),
            $port,
            $username,
            $host
        );
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

            // Kill the ttyd process
            if (isset($session['pid'])) {
                posix_kill($session['pid'], SIGTERM);
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
