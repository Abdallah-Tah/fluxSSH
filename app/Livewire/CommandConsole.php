<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\CommandHistory;
use App\Models\Server;
use App\Services\SSH\SSHManager;
use App\Services\WhispBridge;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
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

    // ... (lines 44-679 omitted)

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
