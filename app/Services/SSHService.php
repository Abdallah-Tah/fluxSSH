<?php

namespace App\Services;

use App\Models\Server;
use Exception;
use Spatie\Ssh\Ssh;

class SSHService
{
    public function __construct()
    {
        // Ensure ssh and sshpass are in PATH for Herd/web server
        $this->ensureSshInPath();
    }

    /**
     * Ensure ssh and sshpass are available in PATH
     */
    private function ensureSshInPath(): void
    {
        $homePath = $_SERVER['HOME'] ?? getenv('HOME');

        // Common paths where ssh might be located on macOS
        $sshPaths = [
            '/usr/bin',
            '/usr/local/bin',
            '/opt/homebrew/bin',
            $homePath . '/bin',
        ];

        $currentPath = getenv('PATH') ?: '';
        $pathsToAdd = [];

        foreach ($sshPaths as $path) {
            if (is_dir($path) && strpos($currentPath, $path) === false) {
                $pathsToAdd[] = $path;
            }
        }

        if (!empty($pathsToAdd)) {
            $newPath = implode(':', $pathsToAdd) . ':' . $currentPath;
            putenv("PATH={$newPath}");
            $_ENV['PATH'] = $newPath;
            $_SERVER['PATH'] = $newPath;
        }
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
            $stdout = method_exists($process, 'getOutput') ? $process->getOutput() : (string) $process;
            $stderr = method_exists($process, 'getErrorOutput') ? $process->getErrorOutput() : '';
            $exitCode = method_exists($process, 'getExitCode') ? $process->getExitCode() : 0;
            $success = $exitCode === 0 || $exitCode === null;

            $output = trim($stdout ?: $stderr);

            return [
                'success' => $success,
                'message' => $success ? 'Connection successful' : ($output ?: 'Connection failed'),
                'error' => $success ? null : ($stderr ? trim($stderr) : 'Connection failed'),
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
            $stderr = '';
            $exitCode = 0;

            if (is_object($result)) {
                // If it's a Process object, get output from it
                $stdout = method_exists($result, 'getOutput') ? $result->getOutput() : '';
                $stderr = method_exists($result, 'getErrorOutput') ? $result->getErrorOutput() : '';
                $output = trim($stdout);

                if (! empty($stderr)) {
                    $output .= "\n" . trim($stderr);
                }

                $exitCode = method_exists($result, 'getExitCode') ? $result->getExitCode() : 0;
                $success = $exitCode === 0 || $exitCode === null;
            } else {
                // If it's already a string, use it directly
                $output = trim((string) $result);
                $stderr = '';
                $exitCode = 0;
                $success = true;
            }

            return [
                'success' => $success,
                'command' => $command,
                'output' => ! empty($output) ? $output : '(Command executed, no output)',
                'error' => $success ? null : (trim($stderr) ?: (! empty($output) ? $output : 'Command failed')),
                'exit_code' => $exitCode,
                'timestamp' => now(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'command' => $command,
                'error' => 'Error: ' . $e->getMessage(),
                'exit_code' => null,
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
    protected function createSshConnection(Server $server): Ssh
    {
        $ssh = Ssh::create($server->username, $server->host, $server->port);

        if ($server->isPasswordAuth()) {
            $ssh->usePassword($server->password);
        } else {
            $ssh->usePrivateKey($this->ensurePrivateKeyFile($server));
        }

        // Disable strict host key checking for easier connections
        $ssh->disableStrictHostKeyChecking();

        // Set connection options if any
        if ($server->connection_options) {
            foreach ($server->connection_options as $option => $value) {
                $ssh->addExtraOption("-o {$option}={$value}");
            }
        }

        // Configure the Symfony Process to use the correct PATH
        $ssh->configureProcess(function ($process) {
            $homePath = $_SERVER['HOME'] ?? getenv('HOME');

            // Include all common paths where ssh/sshpass might be located
            $paths = [
                '/usr/bin',
                '/usr/local/bin',
                '/opt/homebrew/bin',
                $homePath . '/bin',
            ];

            $currentPath = getenv('PATH') ?: '/usr/local/bin:/usr/bin:/bin';
            $fullPath = implode(':', array_filter($paths)) . ':' . $currentPath;

            $env = $process->getEnv() ?: [];
            $env['PATH'] = $fullPath;
            $env['HOME'] = $homePath;
            $process->setEnv($env);
        });

        return $ssh;
    }

    /**
     * Persist the private key content to a readable file for SSH usage.
     */
    protected function ensurePrivateKeyFile(Server $server): string
    {
        $privateKey = $server->private_key;

        if (empty($privateKey)) {
            throw new Exception('Private key not provided.');
        }

        // If a valid path was provided, use it directly
        if (is_file($privateKey) && is_readable($privateKey)) {
            return $privateKey;
        }

        $directory = storage_path('app/ssh_keys');

        if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
            throw new Exception('Unable to create SSH key directory.');
        }

        $keyPath = "{$directory}/server_{$server->id}.key";
        $normalizedKey = trim($privateKey);

        if (! is_file($keyPath) || trim((string) file_get_contents($keyPath)) !== $normalizedKey) {
            file_put_contents($keyPath, $normalizedKey);
            chmod($keyPath, 0600);
        }

        return $keyPath;
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
