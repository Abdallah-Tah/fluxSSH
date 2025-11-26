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

            // Return both stdout and stderr to let the caller decide how to handle them
            return [
                'success' => $result['success'],
                'command' => $command,
                'output' => $result['output'] ?? '',
                'error' => $result['stderr'] ?? '',
                'exit_code' => $result['exit_code'],
                'timestamp' => now(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'command' => $command,
                'output' => '',
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
     * Get comprehensive server statistics
     */
    public function getServerStats(Server $server): array
    {
        try {
            // CPU usage (1 - idle percentage)
            $cpuCmd = "top -bn1 | grep 'Cpu(s)' | awk '{print 100 - $8}'";
            $cpu = $this->executeCommand($server, $cpuCmd);
            $cpuUsage = round((float) trim($cpu['output'] ?? '0'), 1);

            // Memory usage
            $memCmd = "free -m | awk 'NR==2{printf \"%.1f %.1f %.1f\", $3,$2,($3*100/$2)}'";
            $mem = $this->executeCommand($server, $memCmd);
            $memParts = explode(' ', trim($mem['output'] ?? '0 0 0'));
            $memUsed = (float) ($memParts[0] ?? 0);
            $memTotal = (float) ($memParts[1] ?? 1);
            $memPercent = round((float) ($memParts[2] ?? 0), 1);

            // Disk usage (root filesystem)
            $diskCmd = "df -h / | awk 'NR==2{print $3\" \"$2\" \"$5}' | sed 's/%//'";
            $disk = $this->executeCommand($server, $diskCmd);
            $diskParts = explode(' ', trim($disk['output'] ?? '0 0 0'));
            $diskUsed = $diskParts[0] ?? '0';
            $diskTotal = $diskParts[1] ?? '0';
            $diskPercent = (int) ($diskParts[2] ?? 0);

            // OS Info
            $osCmd = "lsb_release -ds 2>/dev/null || cat /etc/*-release 2>/dev/null | grep PRETTY_NAME | cut -d'=' -f2 | tr -d '\"' || uname -s";
            $osInfo = $this->executeCommand($server, $osCmd);

            // Kernel version
            $kernelCmd = 'uname -r';
            $kernel = $this->executeCommand($server, $kernelCmd);

            // Uptime
            $uptimeCmd = "uptime -p 2>/dev/null || uptime | awk -F'( |,|:)+' '{print $6,$7\",\",$8,\"hours,\",$9,\"minutes\"}'";
            $uptime = $this->executeCommand($server, $uptimeCmd);
            $uptimeStr = trim($uptime['output'] ?? 'Unknown');
            // Clean up "up " prefix if present
            $uptimeStr = preg_replace('/^up\s+/', '', $uptimeStr);

            // Update server model with the fetched stats
            $server->update([
                'cpu_usage' => $cpuUsage,
                'memory_usage' => $memPercent,
                'disk_usage' => $diskPercent,
                'os_info' => trim($osInfo['output'] ?? 'Unknown'),
                'kernel_version' => trim($kernel['output'] ?? 'Unknown'),
                'uptime' => $uptimeStr,
                'server_details' => json_encode([
                    'memory_used_mb' => $memUsed,
                    'memory_total_mb' => $memTotal,
                    'disk_used' => $diskUsed,
                    'disk_total' => $diskTotal,
                ]),
                'last_detail_fetch_at' => now(),
            ]);

            return [
                'success' => true,
                'cpu_usage' => $cpuUsage,
                'memory' => [
                    'used' => $memUsed,
                    'total' => $memTotal,
                    'percent' => $memPercent,
                ],
                'disk' => [
                    'used' => $diskUsed,
                    'total' => $diskTotal,
                    'percent' => $diskPercent,
                ],
                'os_info' => trim($osInfo['output'] ?? 'Unknown'),
                'kernel_version' => trim($kernel['output'] ?? 'Unknown'),
                'uptime' => $uptimeStr,
            ];
        } catch (Exception $e) {
            Log::error('Failed to fetch server stats', [
                'server_id' => $server->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
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

        if (! isset($this->connections[$key]) || ! $this->connections[$key]->isConnected()) {
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
