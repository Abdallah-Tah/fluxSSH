<?php

namespace App\Services\Terminal;

use App\Models\Server;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

/**
 * Manages persistent SSH terminal sessions
 *
 * This class maintains SSH connections with PTY enabled for real-time
 * interactive terminal sessions like cloud providers (Hetzner, AWS, etc.)
 */
class TerminalSessionManager
{
    private static array $sessions = [];

    /**
     * Create a new terminal session with PTY
     */
    public static function create(string $sessionId, Server $server, int $cols = 120, int $rows = 40): array
    {
        try {
            $ssh = new SSH2($server->host, $server->port, 30);
            $ssh->setTimeout(0); // No timeout for interactive session

            // Authenticate
            if ($server->auth_type === 'key') {
                $key = PublicKeyLoader::load($server->private_key);
                $authenticated = $ssh->login($server->username, $key);
            } else {
                $authenticated = $ssh->login($server->username, $server->password);
            }

            if (!$authenticated) {
                return [
                    'success' => false,
                    'error' => 'Authentication failed'
                ];
            }

            // Enable PTY for interactive terminal
            $ssh->enablePTY();
            $ssh->setWindowSize($cols, $rows);

            // Start an interactive shell
            $ssh->exec('');

            // Store session
            self::$sessions[$sessionId] = [
                'ssh' => $ssh,
                'server_id' => $server->id,
                'created_at' => now(),
                'cols' => $cols,
                'rows' => $rows,
            ];

            // Update server last connected
            $server->update(['last_connected_at' => now()]);

            Log::info('Terminal session created', [
                'session_id' => $sessionId,
                'server' => $server->name,
            ]);

            return [
                'success' => true,
                'session_id' => $sessionId,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to create terminal session', [
                'error' => $e->getMessage(),
                'server_id' => $server->id,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Write data to the terminal (user input)
     */
    public static function write(string $sessionId, string $data): bool
    {
        if (!isset(self::$sessions[$sessionId])) {
            return false;
        }

        try {
            $ssh = self::$sessions[$sessionId]['ssh'];
            $ssh->write($data);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to write to terminal', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Read available output from the terminal
     */
    public static function read(string $sessionId): ?string
    {
        if (!isset(self::$sessions[$sessionId])) {
            return null;
        }

        try {
            $ssh = self::$sessions[$sessionId]['ssh'];

            // Non-blocking read
            $output = $ssh->read('', SSH2::READ_SIMPLE);

            return $output !== false ? $output : '';
        } catch (\Exception $e) {
            Log::error('Failed to read from terminal', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Resize the terminal window
     */
    public static function resize(string $sessionId, int $cols, int $rows): bool
    {
        if (!isset(self::$sessions[$sessionId])) {
            return false;
        }

        try {
            $ssh = self::$sessions[$sessionId]['ssh'];
            $ssh->setWindowSize($cols, $rows);

            self::$sessions[$sessionId]['cols'] = $cols;
            self::$sessions[$sessionId]['rows'] = $rows;

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to resize terminal', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Close and cleanup a terminal session
     */
    public static function destroy(string $sessionId): bool
    {
        if (!isset(self::$sessions[$sessionId])) {
            return false;
        }

        try {
            $ssh = self::$sessions[$sessionId]['ssh'];
            $ssh->disconnect();

            unset(self::$sessions[$sessionId]);

            Log::info('Terminal session destroyed', [
                'session_id' => $sessionId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to destroy terminal session', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            unset(self::$sessions[$sessionId]);
            return false;
        }
    }

    /**
     * Check if a session exists and is active
     */
    public static function exists(string $sessionId): bool
    {
        return isset(self::$sessions[$sessionId]);
    }

    /**
     * Get session info
     */
    public static function getInfo(string $sessionId): ?array
    {
        if (!isset(self::$sessions[$sessionId])) {
            return null;
        }

        return [
            'server_id' => self::$sessions[$sessionId]['server_id'],
            'created_at' => self::$sessions[$sessionId]['created_at'],
            'cols' => self::$sessions[$sessionId]['cols'],
            'rows' => self::$sessions[$sessionId]['rows'],
        ];
    }

    /**
     * Get all active sessions count
     */
    public static function getActiveCount(): int
    {
        return count(self::$sessions);
    }
}
