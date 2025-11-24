<?php

namespace App\Services;

use App\Models\Server;
use Spatie\Ssh\Ssh;
use Exception;

class SSHService
{
    public function __construct()
    {
        // Initialize SSH service
    }

    /**
     * Test connection to a server
     */
    public function testConnection(Server $server): array
    {
        try {
            $ssh = $this->createSshConnection($server);
            $process = $ssh->execute(['echo "Connection successful"']);
            $output = is_string($process) ? $process : (string) $process;

            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => trim($output)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'output' => null
            ];
        }
    }

    /**
     * Execute a single command on the server
     */
    public function executeCommand(Server $server, string $command): array
    {
        try {
            $ssh = $this->createSshConnection($server);
            $process = $ssh->execute([$command]);

            // Convert process to string output
            $output = is_string($process) ? $process : (string) $process;

            return [
                'success' => true,
                'command' => $command,
                'output' => trim($output),
                'timestamp' => now()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'command' => $command,
                'error' => $e->getMessage(),
                'timestamp' => now()
            ];
        }
    }

    /**
     * Execute multiple commands in sequence
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
     * Create SSH connection instance
     */
    private function createSshConnection(Server $server): Ssh
    {
        $ssh = Ssh::create($server->username, $server->host, $server->port);

        if ($server->isPasswordAuth()) {
            $ssh->usePassword($server->password);
        } else {
            $ssh->usePrivateKey($server->private_key);
        }

        // Set connection options if any
        if ($server->connection_options) {
            foreach ($server->connection_options as $option => $value) {
                $ssh->addExtraOption("-o {$option}={$value}");
            }
        }

        return $ssh;
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
                'system_info' => $systemInfo['success'] ? $systemInfo['output'] : 'N/A',
                'current_directory' => $currentDir['success'] ? $currentDir['output'] : 'N/A',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
