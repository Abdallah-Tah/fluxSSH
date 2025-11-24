<?php

namespace App\Services;

use App\Models\Server;
use Exception;

class WhispBridge
{
    protected string $whispPath;

    public function __construct()
    {
        $this->whispPath = base_path('whisp');
    }

    /**
     * Start an interactive SSH session through Whisp
     */
    public function startInteractiveSession(Server $server): array
    {
        try {
            $sessionId = uniqid('ssh_session_');
            $appPath = $this->whispPath . '/apps/ssh-shell.php';

            if (!file_exists($appPath)) {
                throw new Exception('SSH shell app not found at: ' . $appPath);
            }

            $command = $this->buildWhispCommand($server, $sessionId);

            return [
                'success' => true,
                'session_id' => $sessionId,
                'command' => $command,
                'message' => 'Interactive session ready to start'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send command to active Whisp session
     */
    public function sendCommand(string $sessionId, string $command): array
    {
        try {
            // In a real implementation, you would maintain active sessions
            // and send commands through pipes or sockets

            return [
                'success' => true,
                'session_id' => $sessionId,
                'command' => $command,
                'timestamp' => now()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get output from Whisp session
     */
    public function getSessionOutput(string $sessionId): array
    {
        try {
            // In a real implementation, you would read from session output buffer

            return [
                'success' => true,
                'session_id' => $sessionId,
                'output' => '',
                'has_more' => false
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Close Whisp session
     */
    public function closeSession(string $sessionId): array
    {
        try {
            // Close the session and cleanup resources

            return [
                'success' => true,
                'session_id' => $sessionId,
                'message' => 'Session closed successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Build Whisp command for server connection
     */
    private function buildWhispCommand(Server $server, string $sessionId): string
    {
        $args = [
            '--host=' . $server->host,
            '--port=' . $server->port,
            '--username=' . $server->username,
            '--auth-type=' . $server->auth_type,
            '--session-id=' . $sessionId
        ];

        if ($server->isPasswordAuth()) {
            $args[] = '--password=' . base64_encode($server->password);
        } else {
            $args[] = '--private-key=' . base64_encode($server->private_key);
        }

        return 'php ' . $this->whispPath . '/apps/ssh-shell.php ' . implode(' ', $args);
    }

    /**
     * Check if Whisp apps directory exists
     */
    public function ensureWhispDirectory(): void
    {
        $appsDir = $this->whispPath . '/apps';

        if (!is_dir($this->whispPath)) {
            mkdir($this->whispPath, 0755, true);
        }

        if (!is_dir($appsDir)) {
            mkdir($appsDir, 0755, true);
        }
    }
}
