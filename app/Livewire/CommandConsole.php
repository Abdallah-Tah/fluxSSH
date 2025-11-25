<?php

namespace App\Livewire;

use App\Models\Server;
use App\Services\SSH\SSHManager;
use App\Services\WhispBridge;
use Livewire\Component;

class CommandConsole extends Component
{
    public Server $server;

    public string $command = '';

    public array $history = [];

    public array $output = [];

    public bool $connected = false;

    public ?string $sessionId = null;

    public bool $isLoading = false;

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
                $this->addToOutput('Current directory: ' . $serverInfo['current_directory'], 'info');
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
        $this->addToOutput('$ ' . $command, 'command');

        $this->isLoading = true;

        $ssh = app(SSHManager::class);
        $result = $ssh->executeCommand($this->server, $command);

        if ($result['success']) {
            $this->addToOutput($result['output'], 'output');
        } else {
            $errorOutput = $result['error'] ?? ($result['output'] ?? null) ?? 'Unknown error occurred';
            $this->addToOutput($errorOutput, 'error');
        }

        $this->isLoading = false;
        $this->command = '';

        // Focus input after execution
        $this->dispatch('focusInput');
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
