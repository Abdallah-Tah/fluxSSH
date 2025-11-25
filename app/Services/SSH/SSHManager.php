<?php

namespace App\Services\SSH;

use App\Models\Server;
use Exception;
use Illuminate\Support\Facades\Log;

class SSHManager
{
    protected array $connections = [];

    /**
     * Create an SSH connection for a server
     */
    public function createConnection(Server $server): SSHConnection
    {
        $connection = SSHConnection::create($server->host, $server->username, $server->port);

        if ($server->isPasswordAuth()) {
            $connection->withPassword($server->password);
        } else {
            $privateKey = $this->resolvePrivateKey($server);
            $connection->withPrivateKey($privateKey);
        }

        return $connection;
    }

    /**
     * Test connection to a server
     */
    public function testConnection(Server $server): array
    {
        try {
            $connection = $this->createConnection($server);
            $connection->connect();

            $result = $connection->execute('echo "Connection successful"');
            $connection->disconnect();

            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => $result['output'],
            ];
        } catch (Exception $e) {
            Log::error('SSH connection test failed', [
                'server_id' => $server->id,
                'host' => $server->host,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'output' => null,
            ];
        }
    }

    /**
     * Execute a single command on a server
     */
    public function executeCommand(Server $server, string $command): array
    {
        try {
            $connection = $this->getOrCreateConnection($server);

            $result = $connection->execute($command);

            return [
                'success' => $result['success'],
                'command' => $command,
                'output' => $result['output'] ?: '(Command executed, no output)',
                'error' => $result['success'] ? null : ($result['stderr'] ?: 'Command failed'),
                'exit_code' => $result['exit_code'],
                'timestamp' => now(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'command' => $command,
                'output' => null,
                'error' => $e->getMessage(),
                'exit_code' => -1,
                'timestamp' => now(),
            ];
        }
    }

    /**
     * Execute multiple commands on a server
     */
    public function executeCommands(Server $server, array $commands): array
    {
        $results = [];
        foreach ($commands as $command) {
            $results[] = $this->executeCommand($server, $command);
        }
        return $results;
    }

    /**
     * Get server information
     */
    public function getServerInfo(Server $server): array
    {
        try {
            $systemInfo = $this->executeCommand($server, 'uname -s -r');
            $currentDir = $this->executeCommand($server, 'pwd');

            return [
                'success' => true,
                'system_info' => $systemInfo['output'] ?? 'Unknown',
                'current_directory' => $currentDir['output'] ?? '~',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'system_info' => 'Unknown',
                'current_directory' => '~',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get or create a cached connection for a server
     */
    protected function getOrCreateConnection(Server $server): SSHConnection
    {
        $key = "server_{$server->id}";

        if (!isset($this->connections[$key]) || !$this->connections[$key]->isConnected()) {
            $connection = $this->createConnection($server);
            $connection->connect();
            $this->connections[$key] = $connection;
        }

        return $this->connections[$key];
    }

    /**
     * Resolve private key to actual content
     */
    protected function resolvePrivateKey(Server $server): string
    {
        $privateKey = $server->private_key;

        if (empty($privateKey)) {
            throw new Exception('Private key not provided');
        }

        // If it's a file path, read the content
        if (is_file($privateKey) && is_readable($privateKey)) {
            return file_get_contents($privateKey);
        }

        // Otherwise, assume it's the key content
        return $privateKey;
    }

    /**
     * Close a connection for a server
     */
    public function closeConnection(Server $server): void
    {
        $key = "server_{$server->id}";

        if (isset($this->connections[$key])) {
            $this->connections[$key]->disconnect();
            unset($this->connections[$key]);
        }
    }

    /**
     * Close all connections
     */
    public function closeAllConnections(): void
    {
        foreach ($this->connections as $connection) {
            $connection->disconnect();
        }
        $this->connections = [];
    }

    public function __destruct()
    {
        $this->closeAllConnections();
    }
}
