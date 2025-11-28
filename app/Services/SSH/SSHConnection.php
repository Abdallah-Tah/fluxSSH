<?php

namespace App\Services\SSH;

use Exception;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class SSHConnection
{
    protected ?SSH2 $connection = null;

    protected string $host;

    protected int $port;

    protected string $username;

    protected ?string $password = null;

    protected ?string $privateKey = null;

    protected int $timeout = 10;

    protected bool $connected = false;

    public function __construct(string $host, string $username, int $port = 22)
    {
        $this->host = $host;
        $this->username = $username;
        $this->port = $port;
    }

    /**
     * Create a new SSH connection instance
     */
    public static function create(string $host, string $username, int $port = 22): self
    {
        return new self($host, $username, $port);
    }

    /**
     * Set password for authentication
     */
    public function withPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set private key for authentication
     */
    public function withPrivateKey(string $privateKey, ?string $passphrase = null): self
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Set connection timeout
     */
    public function timeout(int $seconds): self
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * Connect to the SSH server
     */
    public function connect(): bool
    {
        try {
            $this->connection = new SSH2($this->host, $this->port, $this->timeout);

            // Authenticate
            if ($this->privateKey) {
                $key = PublicKeyLoader::load($this->privateKey);
                $authenticated = $this->connection->login($this->username, $key);
            } elseif ($this->password) {
                $authenticated = $this->connection->login($this->username, $this->password);
            } else {
                throw new Exception('No authentication method provided');
            }

            if (! $authenticated) {
                throw new Exception('Authentication failed');
            }

            // Don't enable PTY here - it interferes with exec() output
            // PTY should only be enabled for interactive sessions

            $this->connected = true;

            return true;
        } catch (Exception $e) {
            $this->connected = false;
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Execute a command on the remote server
     */
    public function execute(string $command): array
    {
        if (! $this->connected || ! $this->connection) {
            $this->connect();
        }

        try {
            // Don't use quiet mode - it suppresses output
            // $this->connection->enableQuietMode();

            $output = $this->connection->exec($command);
            $stderr = $this->connection->getStdError();
            $exitCode = $this->connection->getExitStatus();

            // Combine stdout and stderr for display
            $combinedOutput = trim($output);
            if (!empty($stderr) && $exitCode !== 0) {
                $combinedOutput = !empty($combinedOutput)
                    ? $combinedOutput . "\n" . trim($stderr)
                    : trim($stderr);
            }

            return [
                'success' => $exitCode === 0 || $exitCode === false,
                'output' => $combinedOutput,
                'stderr' => trim($stderr),
                'exit_code' => $exitCode === false ? 0 : $exitCode,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'output' => '',
                'stderr' => $e->getMessage(),
                'exit_code' => -1,
            ];
        }
    }

    /**
     * Execute multiple commands
     */
    public function executeMultiple(array $commands): array
    {
        $results = [];
        foreach ($commands as $command) {
            $results[] = $this->execute($command);
        }

        return $results;
    }

    /**
     * Check if connected
     */
    public function isConnected(): bool
    {
        return $this->connected && $this->connection && $this->connection->isConnected();
    }

    /**
     * Disconnect from the server
     */
    public function disconnect(): void
    {
        if ($this->connection) {
            $this->connection->disconnect();
        }
        $this->connected = false;
        $this->connection = null;
    }

    /**
     * Get the underlying SSH2 connection
     */
    public function getConnection(): ?SSH2
    {
        return $this->connection;
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
