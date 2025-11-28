<?php

namespace App\Services\Terminal;

use App\Models\Server;
use phpseclib3\Net\SSH2;

class TerminalSession
{
    private SSH2 $connection;

    private $shell;

    private bool $isActive = false;

    public function __construct(
        private Server $server,
        private string $sessionId
    ) {}

    public function connect(): array
    {
        try {
            $this->connection = new SSH2($this->server->host, $this->server->port);

            // Authenticate
            if ($this->server->auth_type === 'key') {
                $key = \phpseclib3\Crypt\PublicKeyLoader::load($this->server->private_key);
                $authenticated = $this->connection->login($this->server->username, $key);
            } else {
                $authenticated = $this->connection->login($this->server->username, $this->server->password);
            }

            if (! $authenticated) {
                return ['success' => false, 'error' => 'Authentication failed'];
            }

            // Request a PTY (pseudo-terminal)
            $this->connection->enablePTY();

            // Set terminal type and size
            $this->connection->setTerminal('xterm-256color');
            $this->connection->setWindowSize(80, 24);

            // Start shell
            $this->connection->write("export TERM=xterm-256color\n");
            $this->connection->write("stty cols 80 rows 24\n");

            $this->isActive = true;

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function write(string $data): void
    {
        if ($this->isActive && $this->connection) {
            $this->connection->write($data);
        }
    }

    public function read(): string
    {
        if ($this->isActive && $this->connection) {
            return $this->connection->read('', SSH2::READ_SIMPLE);
        }

        return '';
    }

    public function resize(int $cols, int $rows): void
    {
        if ($this->isActive && $this->connection) {
            $this->connection->setWindowSize($cols, $rows);
            $this->connection->write("stty cols {$cols} rows {$rows}\n");
        }
    }

    public function disconnect(): void
    {
        if ($this->connection) {
            $this->connection->disconnect();
        }

        $this->isActive = false;
    }

    public function isActive(): bool
    {
        return $this->isActive && $this->connection->isConnected();
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }
}
