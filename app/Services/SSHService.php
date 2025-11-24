<?php

namespace App\Services;

use App\Models\Server;
use Exception;
use Spatie\Ssh\Ssh;

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

            // Get output from Process object
            $stdout = method_exists($process, 'getOutput') ? $process->getOutput() : '';
            $stderr = method_exists($process, 'getErrorOutput') ? $process->getErrorOutput() : '';

            $output = trim($stdout ?: $stderr);

            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => $output,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'output' => null,
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

            // Execute the command and get the result directly as string
            $result = $ssh->execute([$command]);

            // Spatie SSH returns the process output directly
            $output = '';

            if (is_object($result)) {
                // If it's a Process object, get output from it
                $stdout = method_exists($result, 'getOutput') ? $result->getOutput() : '';
                $stderr = method_exists($result, 'getErrorOutput') ? $result->getErrorOutput() : '';
                $output = trim($stdout);

                if (! empty($stderr)) {
                    $output .= "\n".trim($stderr);
                }

                $exitCode = method_exists($result, 'getExitCode') ? $result->getExitCode() : 0;
                $success = $exitCode === 0 || $exitCode === null;
            } else {
                // If it's already a string, use it directly
                $output = trim((string) $result);
                $success = true;
            }

            return [
                'success' => $success,
                'command' => $command,
                'output' => ! empty($output) ? $output : '(Command executed, no output)',
                'timestamp' => now(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'command' => $command,
                'error' => 'Error: '.$e->getMessage(),
                'timestamp' => now(),
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

        // Disable strict host key checking for easier connections
        $ssh->disableStrictHostKeyChecking();

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
                'error' => $e->getMessage(),
            ];
        }
    }
}
