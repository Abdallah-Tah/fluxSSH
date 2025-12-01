<?php

namespace App\Services\Terminal;

use App\Models\Server;
use Illuminate\Support\Facades\Log;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

/**
 * Manages SSH terminal connections for the application.
 * Uses a singleton pattern to maintain persistent connections.
 */
class SSHTerminalManager
{
    private static array $connections = [];

    /**
     * Get or create an SSH connection for a session
     */
    public static function getConnection(string $sessionId): ?SSH2
    {
        return self::$connections[$sessionId]['ssh'] ?? null;
    }

    /**
     * Create a new SSH connection
     */
    public static function connect(Server $server, string $sessionId): array
    {
        try {
            // Disconnect existing if any
            self::disconnect($sessionId);

            Log::info('[SSHTerminalManager] Connecting', [
                'session_id' => $sessionId,
                'server' => $server->name,
                'host' => $server->host,
            ]);

            $ssh = new SSH2($server->host, $server->port, 10);

            // Authenticate
            if ($server->auth_type === 'key') {
                $key = PublicKeyLoader::load($server->private_key);
                $authenticated = $ssh->login($server->username, $key);
            } else {
                $authenticated = $ssh->login($server->username, $server->password);
            }

            if (! $authenticated) {
                Log::error('[SSHTerminalManager] Authentication failed', ['session_id' => $sessionId]);

                return ['success' => false, 'error' => 'Authentication failed'];
            }

            // Enable PTY for interactive terminal
            $ssh->enablePTY();
            $ssh->setTerminal('xterm-256color');
            $ssh->setWindowSize(120, 30);

            // Start shell
            $ssh->exec('');

            // Wait for initial prompt
            usleep(300000);
            $ssh->setTimeout(0.1);
            $initialOutput = $ssh->read('', SSH2::READ_SIMPLE) ?: '';

            // Store connection
            self::$connections[$sessionId] = [
                'ssh' => $ssh,
                'server_id' => $server->id,
                'created_at' => now(),
            ];

            Log::info('[SSHTerminalManager] Connected successfully', ['session_id' => $sessionId]);

            return [
                'success' => true,
                'initial_output' => $initialOutput,
            ];
        } catch (\Exception $e) {
            Log::error('[SSHTerminalManager] Connection error', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Write data to the SSH connection
     */
    public static function write(string $sessionId, string $data): bool
    {
        $ssh = self::getConnection($sessionId);

        if (! $ssh || ! $ssh->isConnected()) {
            return false;
        }

        try {
            $ssh->write($data);

            return true;
        } catch (\Exception $e) {
            Log::error('[SSHTerminalManager] Write error', ['error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Read data from the SSH connection
     */
    public static function read(string $sessionId): string
    {
        $ssh = self::getConnection($sessionId);

        if (! $ssh || ! $ssh->isConnected()) {
            return '';
        }

        try {
            $ssh->setTimeout(0.05);

            return $ssh->read('', SSH2::READ_SIMPLE) ?: '';
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Resize the terminal
     */
    public static function resize(string $sessionId, int $cols, int $rows): bool
    {
        $ssh = self::getConnection($sessionId);

        if (! $ssh || ! $ssh->isConnected()) {
            return false;
        }

        try {
            $ssh->setWindowSize($cols, $rows);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if connection is active
     */
    public static function isConnected(string $sessionId): bool
    {
        $ssh = self::getConnection($sessionId);

        return $ssh && $ssh->isConnected();
    }

    /**
     * Disconnect a session
     */
    public static function disconnect(string $sessionId): void
    {
        if (isset(self::$connections[$sessionId])) {
            try {
                $ssh = self::$connections[$sessionId]['ssh'];
                if ($ssh) {
                    $ssh->disconnect();
                }
            } catch (\Exception $e) {
                // Ignore disconnect errors
            }

            unset(self::$connections[$sessionId]);
            Log::info('[SSHTerminalManager] Disconnected', ['session_id' => $sessionId]);
        }
    }

    /**
     * Get all active session IDs
     */
    public static function getActiveSessions(): array
    {
        return array_keys(self::$connections);
    }

    /**
     * Clean up old/dead connections
     */
    public static function cleanup(): void
    {
        foreach (self::$connections as $sessionId => $data) {
            $ssh = $data['ssh'] ?? null;
            $createdAt = $data['created_at'] ?? null;

            // Disconnect if not connected or older than 2 hours
            if (! $ssh || ! $ssh->isConnected() || ($createdAt && $createdAt->diffInHours(now()) > 2)) {
                self::disconnect($sessionId);
            }
        }
    }
}
