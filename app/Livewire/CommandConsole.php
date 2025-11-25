<?php

namespace App\Livewire;

use App\Models\Server;
use App\Services\SSH\SSHManager;
use App\Services\WhispBridge;
use Livewire\Component;
use Livewire\Attributes\On;

class CommandConsole extends Component
{
    public Server $server;

    public string $command = '';

    public array $history = [];

    public array $output = [];

    public bool $connected = false;

    public ?string $sessionId = null;

    public bool $isLoading = false;

    public string $currentDirectory = '~';

    public array $tabCompletions = [];

    public int $tabIndex = -1;

    protected $listeners = [
        'echo:ssh-output,CommandOutput' => 'handleOutput',
    ];

    public function mount(Server $server): void
    {
        $this->server = $server;
        $this->initializeConnection();
    }

    public function initializeConnection(): void
    {
        $this->isLoading = true;

        $ssh = app(SSHManager::class);
        $connectionTest = $ssh->testConnection($this->server);

        if ($connectionTest['success']) {
            $this->connected = true;
            $this->addToOutput('Connected to ' . $this->server->getConnectionString(), 'success');

            // Get server info
            $serverInfo = $ssh->getServerInfo($this->server);
            if ($serverInfo['success']) {
                $this->addToOutput('System: ' . $serverInfo['system_info'], 'info');
                $this->currentDirectory = $serverInfo['current_directory'];
                $this->addToOutput('Current directory: ' . $this->currentDirectory, 'info');
            }
        } else {
            $this->connected = false;
            $this->addToOutput('Connection failed: ' . $connectionTest['message'], 'error');
        }

        $this->isLoading = false;
    }

    public function executeCommand(): void
    {
        if (empty(trim($this->command))) {
            return;
        }

        $command = trim($this->command);
        $this->history[] = $command;
        $this->tabCompletions = [];
        $this->tabIndex = -1;

        // Handle cd command to track current directory
        if (preg_match('/^cd\s+(.+)$/', $command, $matches) || $command === 'cd') {
            $this->addToOutput('$ ' . $command, 'command');
            $this->handleCdCommand($command);
        } else {
            $this->addToOutput('$ ' . $command, 'command');
            $this->runCommand($command);
        }

        $this->command = '';
        $this->dispatch('focusInput');
    }

    private function handleCdCommand(string $command): void
    {
        $this->isLoading = true;
        $ssh = app(SSHManager::class);

        // Execute cd and then pwd to get new directory
        $result = $ssh->executeCommand($this->server, $command . ' && pwd');

        if ($result['success']) {
            $this->currentDirectory = trim($result['output']);
            $this->addToOutput('', 'output'); // Empty output for cd
        } else {
            $this->addToOutput($result['error'] ?? 'Failed to change directory', 'error');
        }

        $this->isLoading = false;
    }

    private function runCommand(string $command): void
    {
        $this->isLoading = true;

        $ssh = app(SSHManager::class);

        // Prepend cd to current directory for each command
        $fullCommand = "cd {$this->currentDirectory} && {$command}";
        $result = $ssh->executeCommand($this->server, $fullCommand);

        if ($result['success']) {
            if (!empty($result['output'])) {
                $this->addToOutput($result['output'], 'output');
            }
        } else {
            $errorOutput = $result['error'] ?? ($result['output'] ?? 'Unknown error occurred');
            $this->addToOutput($errorOutput, 'error');
        }

        $this->isLoading = false;
    }

    /**
     * Tab completion - get suggestions from server
     */
    public function tabComplete(): void
    {
        if (!$this->connected || empty($this->command)) {
            return;
        }

        $ssh = app(SSHManager::class);

        // Parse the command to find what we're completing
        $parts = explode(' ', $this->command);
        $lastPart = end($parts);
        $isFirstWord = count($parts) === 1;

        if ($isFirstWord) {
            // Complete command names
            $result = $ssh->executeCommand(
                $this->server,
                "compgen -c '{$lastPart}' 2>/dev/null | head -20"
            );
        } else {
            // Complete file/directory names
            $escapedPart = escapeshellarg($lastPart . '*');
            $result = $ssh->executeCommand(
                $this->server,
                "cd {$this->currentDirectory} && ls -d {$escapedPart} 2>/dev/null | head -20"
            );
        }

        if ($result['success'] && !empty($result['output'])) {
            $completions = array_filter(explode("\n", trim($result['output'])));

            if (count($completions) === 1) {
                // Single match - complete it
                $completion = $completions[0];
                if ($isFirstWord) {
                    $this->command = $completion . ' ';
                } else {
                    array_pop($parts);
                    $parts[] = $completion;
                    $this->command = implode(' ', $parts);

                    // Add trailing slash for directories or space for files
                    $isDir = $ssh->executeCommand(
                        $this->server,
                        "cd {$this->currentDirectory} && test -d " . escapeshellarg($completion) . " && echo 'dir'"
                    );
                    if ($isDir['success'] && trim($isDir['output']) === 'dir') {
                        $this->command .= '/';
                    } else {
                        $this->command .= ' ';
                    }
                }
                $this->tabCompletions = [];
            } elseif (count($completions) > 1) {
                // Multiple matches - show them and find common prefix
                $this->tabCompletions = $completions;
                $this->tabIndex = -1;

                // Find common prefix
                $commonPrefix = $this->findCommonPrefix($completions);
                if (strlen($commonPrefix) > strlen($lastPart)) {
                    if ($isFirstWord) {
                        $this->command = $commonPrefix;
                    } else {
                        array_pop($parts);
                        $parts[] = $commonPrefix;
                        $this->command = implode(' ', $parts);
                    }
                }

                // Show completions in output
                $this->addToOutput(implode('  ', $completions), 'info');
            }
        }

        $this->dispatch('focusInput');
    }

    /**
     * Cycle through tab completions
     */
    public function cycleCompletion(int $direction = 1): void
    {
        if (empty($this->tabCompletions)) {
            return;
        }

        $this->tabIndex += $direction;

        if ($this->tabIndex >= count($this->tabCompletions)) {
            $this->tabIndex = 0;
        } elseif ($this->tabIndex < 0) {
            $this->tabIndex = count($this->tabCompletions) - 1;
        }

        $parts = explode(' ', $this->command);
        array_pop($parts);
        $parts[] = $this->tabCompletions[$this->tabIndex];
        $this->command = implode(' ', $parts);
    }

    private function findCommonPrefix(array $strings): string
    {
        if (empty($strings)) {
            return '';
        }

        $prefix = $strings[0];
        foreach ($strings as $string) {
            while (strpos($string, $prefix) !== 0) {
                $prefix = substr($prefix, 0, -1);
                if (empty($prefix)) {
                    return '';
                }
            }
        }
        return $prefix;
    }

    public function startInteractiveSession(): void
    {
        $whispBridge = app(WhispBridge::class);
        $session = $whispBridge->startInteractiveSession($this->server);

        if ($session['success']) {
            $this->sessionId = $session['session_id'];
            $this->addToOutput('Interactive session started (ID: ' . $this->sessionId . ')', 'success');
        } else {
            $this->addToOutput('Failed to start interactive session: ' . $session['error'], 'error');
        }
    }

    public function clearOutput(): void
    {
        $this->output = [];
        $this->addToOutput('Terminal cleared', 'info');
    }

    public function disconnect(): void
    {
        // Close SSH connection
        $ssh = app(SSHManager::class);
        $ssh->closeConnection($this->server);

        if ($this->sessionId) {
            $whispBridge = app(WhispBridge::class);
            $whispBridge->closeSession($this->sessionId);
            $this->sessionId = null;
        }

        $this->connected = false;
        $this->addToOutput('Disconnected from ' . $this->server->getConnectionString(), 'info');
    }

    public function handleOutput($data): void
    {
        if (isset($data['session_id']) && $data['session_id'] === $this->sessionId) {
            $this->addToOutput($data['output'], 'output');
        }
    }

    private function addToOutput(string $text, string $type = 'output'): void
    {
        // Skip empty output lines for cleaner display
        if ($type === 'output' && empty(trim($text))) {
            return;
        }

        $this->output[] = [
            'text' => $text,
            'type' => $type,
            'timestamp' => now()->format('H:i:s'),
        ];

        // Keep only last 1000 lines
        if (count($this->output) > 1000) {
            $this->output = array_slice($this->output, -1000);
        }
    }

    public function getCommandHistory(): array
    {
        return array_reverse($this->history);
    }

    public function getPrompt(): string
    {
        $dir = $this->currentDirectory;
        $home = '/root';

        // Replace home directory with ~
        if (str_starts_with($dir, $home)) {
            $dir = '~' . substr($dir, strlen($home));
        }

        return $dir;
    }

    public function getLineClass(string $type): string
    {
        return match ($type) {
            'command' => 'text-cyan-400 font-semibold',
            'output' => 'text-zinc-100',
            'error' => 'text-red-400 font-medium',
            'success' => 'text-emerald-400 font-medium',
            'info' => 'text-sky-400',
            default => 'text-zinc-300'
        };
    }

    public function render()
    {
        return view('livewire.command-console');
    }
}
