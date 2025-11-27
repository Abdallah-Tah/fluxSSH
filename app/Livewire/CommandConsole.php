<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\CommandHistory;
use App\Models\Server;
use App\Services\SSH\SSHManager;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CommandConsole extends Component
{
    public Server $server;

    public string $command = '';

    public array $history = [];

    public array $output = [];

    public int $outputCount = 0;

    public bool $connected = false;

    public ?string $sessionId = null;

    public bool $isLoading = false;

    public string $currentDirectory = '~';

    public array $tabCompletions = [];

    public int $tabIndex = -1;

    public string $theme = 'saturn'; // saturn, dracula, github-dark, github-light, cyberpunk

    public bool $showHistorySidebar = false;

    public bool $showStats = true;

    public array $bookmarkedCommands = [];

    public string $searchQuery = '';

    public array $executionTimes = [];

    public ?float $lastCommandStartTime = null;

    public int $historyIndex = -1;

    public string $currentInput = '';

    protected $listeners = [
        'echo:ssh-output,CommandOutput' => 'handleOutput',
    ];

    public function mount(Server $server): void
    {
        $this->server = $server;
        $this->loadCommandHistory();
        $this->initializeConnection();
    }

    public function initializeConnection(): void
    {
        $this->isLoading = true;

        Log::info('[SSH Console] Initializing connection', [
            'server_id' => $this->server->id,
            'server_name' => $this->server->name,
            'host' => $this->server->host,
        ]);

        $ssh = app(SSHManager::class);
        $connectionTest = $ssh->testConnection($this->server);

        if ($connectionTest['success']) {
            $this->connected = true;
            $this->output[] = [
                'type' => 'success',
                'text' => 'Connected successfully to '.$this->server->name,
                'timestamp' => now()->format('H:i:s'),
            ];

            // Get initial directory
            $result = $ssh->executeCommand($this->server, 'pwd');
            if (isset($result['output'])) {
                $this->currentDirectory = trim($result['output']);
            }

            // Log successful connection to activity log
            ActivityLog::log(
                type: 'connection',
                action: 'connected',
                description: "Connected to {$this->server->name}",
                serverId: $this->server->id,
                metadata: ['connection_string' => $this->server->getConnectionString()]
            );
        } else {
            $this->output[] = [
                'type' => 'error',
                'text' => 'Connection failed: '.$connectionTest['message'],
                'timestamp' => now()->format('H:i:s'),
            ];

            // Log connection failure
            ActivityLog::log(
                type: 'connection',
                action: 'failed',
                description: "Failed to connect to {$this->server->name}",
                serverId: $this->server->id,
                metadata: ['error' => $connectionTest['message']]
            );
        }

        $this->outputCount++;
        $this->isLoading = false;
    }

    public function executeCommand(): void
    {
        if (empty(trim($this->command))) {
            return;
        }

        $this->lastCommandStartTime = microtime(true);

        $this->history[] = $this->command;
        $this->historyIndex = -1;

        $this->output[] = [
            'type' => 'command',
            'text' => $this->command,
            'timestamp' => now()->format('H:i:s'),
        ];

        $ssh = app(SSHManager::class);
        $result = $ssh->executeCommand($this->server, $this->command);

        $executionTime = microtime(true) - $this->lastCommandStartTime;
        $this->executionTimes[] = $executionTime;

        // Update directory if it's a cd command
        if (preg_match('/^cd\s+(.+)/', $this->command, $matches)) {
            $pwdResult = $ssh->executeCommand($this->server, 'pwd');
            if (isset($pwdResult['output'])) {
                $this->currentDirectory = trim($pwdResult['output']);
            }
        }

        if (isset($result['output'])) {
            $this->output[] = [
                'type' => $result['exit_code'] === 0 ? 'output' : 'error',
                'text' => $result['output'],
                'timestamp' => now()->format('H:i:s'),
            ];

            // Log command execution
            ActivityLog::log(
                type: 'command',
                action: 'executed',
                description: "Executed command: {$this->command}",
                serverId: $this->server->id,
                metadata: [
                    'command' => $this->command,
                    'directory' => $this->currentDirectory,
                    'exit_code' => $result['exit_code'] ?? 0,
                ]
            );
        } else {
            $this->output[] = [
                'type' => 'error',
                'text' => 'Command execution failed',
                'timestamp' => now()->format('H:i:s'),
            ];

            // Log command error
            ActivityLog::log(
                type: 'error',
                action: 'command_failed',
                description: "Command execution failed: {$this->command}",
                serverId: $this->server->id,
                metadata: ['command' => $this->command]
            );
        }

        $this->saveCommandToHistory($this->command, $executionTime);

        $this->outputCount++;
        $this->command = '';

        $this->dispatch('scroll-terminal');
    }

    public function clearOutput(): void
    {
        $this->output = [];
        $this->outputCount = 0;
    }

    public function disconnect(): void
    {
        ActivityLog::log(
            type: 'connection',
            action: 'disconnected',
            description: "Disconnected from {$this->server->name}",
            serverId: $this->server->id
        );

        $this->connected = false;
        $this->redirect(route('servers'));
    }

    public function tabComplete(): void
    {
        if (empty($this->command)) {
            return;
        }

        $ssh = app(SSHManager::class);
        $result = $ssh->executeCommand(
            $this->server,
            "compgen -c {$this->command}"
        );

        if (isset($result['output']) && ! empty($result['output'])) {
            $suggestions = array_filter(explode("\n", $result['output']));
            if (! empty($suggestions)) {
                $this->command = $suggestions[0];
            }
        }
    }

    public function toggleTheme(): void
    {
        $themes = ['saturn', 'dracula', 'github-dark', 'github-light', 'cyberpunk'];
        $currentIndex = array_search($this->theme, $themes);
        $this->theme = $themes[($currentIndex + 1) % count($themes)];
    }

    public function toggleHistorySidebar(): void
    {
        $this->showHistorySidebar = ! $this->showHistorySidebar;
    }

    public function toggleStats(): void
    {
        $this->showStats = ! $this->showStats;
    }

    public function bookmarkCommand(string $command): void
    {
        if (! in_array($command, $this->bookmarkedCommands)) {
            $this->bookmarkedCommands[] = $command;
        }
    }

    public function removeBookmark(int $index): void
    {
        array_splice($this->bookmarkedCommands, $index, 1);
    }

    public function executeBookmark(string $command): void
    {
        $this->command = $command;
        $this->executeCommand();
    }

    public function getFilteredOutput(): array
    {
        if (empty($this->searchQuery)) {
            return $this->output;
        }

        return array_filter($this->output, function ($line) {
            return stripos($line['text'], $this->searchQuery) !== false;
        });
    }

    public function getAverageExecutionTime(): float
    {
        if (empty($this->executionTimes)) {
            return 0;
        }

        return array_sum($this->executionTimes) / count($this->executionTimes);
    }

    private function loadCommandHistory(): void
    {
        $this->history = CommandHistory::query()
            ->where('user_id', auth()->id())
            ->where('server_id', $this->server->id)
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->pluck('command')
            ->toArray();

        Log::debug('[SSH Console] Loaded command history', [
            'server_id' => $this->server->id,
            'count' => count($this->history),
        ]);
    }

    private function saveCommandToHistory(string $command, float $executionTime): void
    {
        CommandHistory::create([
            'user_id' => auth()->id(),
            'server_id' => $this->server->id,
            'command' => $command,
            'current_directory' => $this->currentDirectory,
            'execution_time' => $executionTime,
        ]);

        Log::debug('[SSH Console] Saved command to history', [
            'server_id' => $this->server->id,
            'command' => $command,
            'execution_time' => $executionTime,
        ]);
    }

    public function navigateHistory(string $direction): void
    {
        if (empty($this->history)) {
            return;
        }

        // Save current input when first navigating
        if ($this->historyIndex === -1) {
            $this->currentInput = $this->command;
        }

        $historyCount = count($this->history);

        if ($direction === 'up') {
            // Navigate backwards (older commands)
            if ($this->historyIndex < $historyCount - 1) {
                $this->historyIndex++;
                $this->command = $this->history[$historyCount - 1 - $this->historyIndex];
            }
        } elseif ($direction === 'down') {
            // Navigate forwards (newer commands)
            if ($this->historyIndex > 0) {
                $this->historyIndex--;
                $this->command = $this->history[$historyCount - 1 - $this->historyIndex];
            } elseif ($this->historyIndex === 0) {
                // Return to current input
                $this->historyIndex = -1;
                $this->command = $this->currentInput;
            }
        }

        Log::debug('[SSH Console] History navigation', [
            'direction' => $direction,
            'index' => $this->historyIndex,
            'command' => $this->command,
        ]);
    }

    public function render()
    {
        return view('livewire.command-console');
    }
}
