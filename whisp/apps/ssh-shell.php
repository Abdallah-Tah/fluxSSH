#!/usr/bin/env php
<?php

/**
 * FluxSSH Interactive Shell App for WhispPHP
 *
 * This app provides an interactive SSH session manager that bridges
 * Laravel's SSH services with WhispPHP's interactive shell capabilities.
 */

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../bootstrap/app.php';

use App\Models\Server;
use App\Services\SSH\SSHManager;
use Illuminate\Support\Facades\Log;

class SSHShellApp
{
    private $server;

    private $sshManager;

    private $sessionId;

    private $commandHistory = [];

    private $currentDirectory = '~';

    private $isConnected = false;

    public function __construct($serverId = null)
    {
        $this->sessionId = uniqid('ssh_session_');
        $this->sshManager = new SSHManager;

        if ($serverId) {
            $this->server = Server::findOrFail($serverId);
        }
    }

    public function run(): void
    {
        $this->printWelcome();

        if (! $this->server) {
            $this->selectServer();
        }

        if (! $this->connect()) {
            $this->printError("Failed to connect to server: {$this->server->name}");
            exit(1);
        }

        $this->printConnected();
        $this->interactiveShell();
    }

    private function printWelcome(): void
    {
        echo "\033[1;34m";
        echo "┌─────────────────────────────────────────────────────┐\n";
        echo "│                   FluxSSH Shell                    │\n";
        echo "│                Interactive Terminal                 │\n";
        echo "└─────────────────────────────────────────────────────┘\n";
        echo "\033[0m";
        echo "\n";
    }

    private function selectServer(): void
    {
        $servers = Server::where('is_active', true)->get();

        if ($servers->isEmpty()) {
            $this->printError('No active servers available. Please add servers through the web interface.');
            exit(1);
        }

        echo "Available Servers:\n";
        echo "─────────────────\n";

        foreach ($servers as $index => $server) {
            echo sprintf(
                "%d) %s (%s@%s:%d)\n",
                $index + 1,
                $server->name,
                $server->username,
                $server->host,
                $server->port
            );
        }

        echo "\nSelect a server (1-{$servers->count()}): ";
        $choice = (int) trim(fgets(STDIN));

        if ($choice < 1 || $choice > $servers->count()) {
            $this->printError('Invalid selection.');
            exit(1);
        }

        $this->server = $servers[$choice - 1];
    }

    private function connect(): bool
    {
        try {
            $result = $this->sshManager->testConnection($this->server);

            if ($result['success']) {
                $this->isConnected = true;
                $this->server->update(['last_connected_at' => now()]);

                // Get initial directory
                $pwdResult = $this->sshManager->executeCommand($this->server, 'pwd');
                if ($pwdResult['success']) {
                    $this->currentDirectory = trim($pwdResult['output']);
                }

                return true;
            }

            $this->printError($result['error']);

            return false;
        } catch (\Exception $e) {
            $this->printError($e->getMessage());

            return false;
        }
    }

    private function printConnected(): void
    {
        echo "\033[1;32m";
        echo "✓ Connected to: {$this->server->name} ({$this->server->host})\n";
        echo "\033[0m";
        echo "Type 'help' for available commands, 'exit' to quit.\n\n";
    }

    private function interactiveShell(): void
    {
        while ($this->isConnected) {
            $prompt = $this->buildPrompt();
            echo $prompt;

            $command = trim(fgets(STDIN));

            if (empty($command)) {
                continue;
            }

            $this->addToHistory($command);

            if ($this->handleBuiltinCommand($command)) {
                continue;
            }

            $this->executeCommand($command);
        }
    }

    private function buildPrompt(): string
    {
        $user = $this->server->username;
        $host = $this->server->name;
        $dir = $this->getShortPath($this->currentDirectory);

        return "\033[1;36m{$user}@{$host}\033[0m:\033[1;34m{$dir}\033[0m$ ";
    }

    private function getShortPath(string $path): string
    {
        $homeDir = '/home/' . $this->server->username;

        if (str_starts_with($path, $homeDir)) {
            return '~' . substr($path, strlen($homeDir));
        }

        return $path;
    }

    private function handleBuiltinCommand(string $command): bool
    {
        $parts = explode(' ', $command);
        $cmd = $parts[0];

        switch ($cmd) {
            case 'exit':
            case 'logout':
            case 'quit':
                $this->isConnected = false;
                echo "\033[1;32mGoodbye!\033[0m\n";

                return true;

            case 'help':
                $this->printHelp();

                return true;

            case 'history':
                $this->printHistory();

                return true;

            case 'clear':
                echo "\033[2J\033[H";

                return true;

            case 'info':
                $this->printServerInfo();

                return true;

            default:
                return false;
        }
    }

    private function executeCommand(string $command): void
    {
        try {
            // Handle cd command specially to track directory
            if (str_starts_with($command, 'cd ')) {
                $this->handleCdCommand($command);

                return;
            }

            $result = $this->sshManager->executeCommand($this->server, $command);

            if ($result['success']) {
                echo $result['output'];
                if (! str_ends_with($result['output'], "\n")) {
                    echo "\n";
                }
            } else {
                $this->printError($result['error']);
            }

            Log::info('SSH command executed', [
                'session_id' => $this->sessionId,
                'server' => $this->server->name,
                'command' => $command,
                'success' => $result['success'],
            ]);
        } catch (\Exception $e) {
            $this->printError('Command execution failed: ' . $e->getMessage());
        }
    }

    private function handleCdCommand(string $command): void
    {
        $result = $this->sshManager->executeCommand($this->server, $command . ' && pwd');

        if ($result['success']) {
            $this->currentDirectory = trim($result['output']);
        } else {
            $this->printError($result['error']);
        }
    }

    private function addToHistory(string $command): void
    {
        $this->commandHistory[] = [
            'command' => $command,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        // Keep only last 100 commands
        if (count($this->commandHistory) > 100) {
            array_shift($this->commandHistory);
        }
    }

    private function printHistory(): void
    {
        echo "Command History:\n";
        echo "───────────────\n";

        $recent = array_slice($this->commandHistory, -20);

        foreach ($recent as $index => $entry) {
            echo sprintf(
                "%3d  %s  %s\n",
                $index + 1,
                $entry['timestamp'],
                $entry['command']
            );
        }
        echo "\n";
    }

    private function printServerInfo(): void
    {
        echo "\033[1;33mServer Information:\033[0m\n";
        echo "──────────────────\n";
        echo "Name: {$this->server->name}\n";
        echo "Host: {$this->server->host}:{$this->server->port}\n";
        echo "Username: {$this->server->username}\n";
        echo "Auth Type: {$this->server->auth_type}\n";
        echo "Current Directory: {$this->currentDirectory}\n";
        echo "Session ID: {$this->sessionId}\n";
        echo "\n";
    }

    private function printHelp(): void
    {
        echo "\033[1;33mFluxSSH Shell Commands:\033[0m\n";
        echo "─────────────────────\n";
        echo "Built-in commands:\n";
        echo "  help      Show this help message\n";
        echo "  history   Show command history\n";
        echo "  clear     Clear the screen\n";
        echo "  info      Show server information\n";
        echo "  exit      Exit the shell\n";
        echo "\n";
        echo "All other commands are executed on the remote server.\n";
        echo "Use standard Unix commands like ls, pwd, cat, etc.\n\n";
    }

    private function printError(string $message): void
    {
        echo "\033[1;31mError: {$message}\033[0m\n";
    }
}

// Handle command line arguments
$serverId = $argv[1] ?? null;

if ($serverId && ! is_numeric($serverId)) {
    echo "Usage: php ssh-shell.php [server_id]\n";
    exit(1);
}

try {
    $app = new SSHShellApp($serverId);
    $app->run();
} catch (\Exception $e) {
    echo "\033[1;31mFatal Error: {$e->getMessage()}\033[0m\n";
    exit(1);
}
