<?php

namespace App\Services\Terminal;

use App\Models\Server;
use Illuminate\Support\Facades\Log;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class TerminalSession
{
    private ?SSH2 $connection = null;

    private bool $isActive = false;

    private string $buffer = '';

    public function __construct(
        private Server $server,
        private string $sessionId
    ) {}

    public function connect(): array
    {
        try {
            Log::info('[TerminalSession] Connecting', [
                'session_id' => $this->sessionId,
                'server' => $this->server->name,
                'host' => $this->server->host,
            ]);

            $this->connection = new SSH2($this->server->host, $this->server->port, 10);

            // Authenticate
            if ($this->server->auth_type === 'key') {
                $key = PublicKeyLoader::load($this->server->private_key);
                $authenticated = $this->connection->login($this->server->username, $key);
            } else {
                $authenticated = $this->connection->login($this->server->username, $this->server->password);
            }

            if (! $authenticated) {
                Log::error('[TerminalSession] Authentication failed', ['session_id' => $this->sessionId]);

                return ['success' => false, 'error' => 'Authentication failed'];
            }

            // Enable PTY for interactive terminal
            $this->connection->enablePTY();

            // Set terminal type
            $this->connection->setTerminal('xterm-256color');
            $this->connection->setWindowSize(120, 30);

            // Start interactive shell
            $this->connection->exec('');

            // Read initial prompt
            usleep(500000); // Wait for prompt
            $this->buffer = $this->connection->read('', SSH2::READ_SIMPLE) ?: '';

            $this->isActive = true;

            Log::info('[TerminalSession] Connected successfully', ['session_id' => $this->sessionId]);

            return [
                'success' => true,
                'initial_output' => $this->buffer,
            ];
        } catch (\Exception $e) {
            Log::error('[TerminalSession] Connection error', [
                'session_id' => $this->sessionId,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function write(string $data): void
    {
        if ($this->isActive && $this->connection && $this->connection->isConnected()) {
            $this->connection->write($data);
        }
    }

    public function read(): string
    {
        if ($this->isActive && $this->connection && $this->connection->isConnected()) {
            // Non-blocking read with timeout
            $this->connection->setTimeout(0.1);
            $output = $this->connection->read('', SSH2::READ_SIMPLE);

            return $output ?: '';
        }

        return '';
    }

    public function resize(int $cols, int $rows): void
    {
        if ($this->isActive && $this->connection && $this->connection->isConnected()) {
            $this->connection->setWindowSize($cols, $rows);
        }
    }

    public function disconnect(): void
    {
        if ($this->connection) {
            try {
                $this->connection->disconnect();
            } catch (\Exception $e) {
                // Ignore disconnect errors
            }
        }

        $this->isActive = false;
        Log::info('[TerminalSession] Disconnected', ['session_id' => $this->sessionId]);
    }

    public function isActive(): bool
    {
        return $this->isActive && $this->connection && $this->connection->isConnected();
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }
}
