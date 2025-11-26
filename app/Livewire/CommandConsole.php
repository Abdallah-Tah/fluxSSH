<?php

namespace App\Livewire;

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

    public string $theme = 'modern'; // modern, retro-green, retro-amber, cyberpunk

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
            $this->addToOutput('Connected to '.$this->server->getConnectionString(), 'success');

            Log::info('[SSH Console] Connection successful', [
                'server_id' => $this->server->id,
            ]);

            // Get server info
            $serverInfo = $ssh->getServerInfo($this->server);
            if ($serverInfo['success']) {
                $this->addToOutput('System: '.$serverInfo['system_info'], 'info');
                $this->currentDirectory = $serverInfo['current_directory'];
                $this->addToOutput('Current directory: '.$this->currentDirectory, 'info');

                Log::info('[SSH Console] Server info retrieved', [
                    'server_id' => $this->server->id,
                    'system_info' => $serverInfo['system_info'],
                    'current_directory' => $this->currentDirectory,
                ]);
            }
        } else {
            $this->connected = false;
            $this->addToOutput('Connection failed: '.$connectionTest['message'], 'error');

            Log::error('[SSH Console] Connection failed', [
                'server_id' => $this->server->id,
                'error' => $connectionTest['message'],
            ]);
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
        $this->historyIndex = -1;
        $this->currentInput = '';

        // Start timing
        $this->lastCommandStartTime = microtime(true);

        Log::info('[SSH Console] Executing command', [
            'server_id' => $this->server->id,
            'command' => $command,
            'current_directory' => $this->currentDirectory,
        ]);

        // Handle cd command to track current directory
        if (preg_match('/^cd\s+(.+)$/', $command, $matches) || $command === 'cd') {
            $this->addToOutput('$ '.$command, 'command');
            $this->handleCdCommand($command);
        } else {
            $this->addToOutput('$ '.$command, 'command');
            $this->runCommand($command);
        }

        // Record execution time and save to database
        if ($this->lastCommandStartTime) {
            $executionTime = microtime(true) - $this->lastCommandStartTime;
            $this->executionTimes[] = $executionTime;
            // Keep only last 50 execution times
            if (count($this->executionTimes) > 50) {
                array_shift($this->executionTimes);
            }

            // Save to database
            $this->saveCommandToHistory($command, $executionTime);

            $this->lastCommandStartTime = null;
        }

        $this->command = '';
        $this->dispatch('focusInput');
    }

    private function handleCdCommand(string $command): void
    {
        $this->isLoading = true;
        $ssh = app(SSHManager::class);

        // First change to current directory, then execute the cd command, then get pwd
        $escapedCurrentDir = escapeshellarg($this->currentDirectory);

        // Extract the path from the cd command
        $newPath = trim(preg_replace('/^cd\s*/', '', $command));

        Log::debug('[SSH Console] Handling cd command', [
            'server_id' => $this->server->id,
            'original_command' => $command,
            'extracted_path' => $newPath,
            'current_directory' => $this->currentDirectory,
        ]);

        if (empty($newPath)) {
            // cd with no args goes to home directory
            $fullCommand = 'cd ~ && pwd';
        } else {
            // Escape the new path argument
            $escapedNewPath = escapeshellarg($newPath);
            $fullCommand = "cd {$escapedCurrentDir} 2>/dev/null; cd {$escapedNewPath} && pwd";
        }

        Log::debug('[SSH Console] CD full command', [
            'full_command' => $fullCommand,
        ]);

        $result = $ssh->executeCommand($this->server, $fullCommand);

        Log::debug('[SSH Console] CD command result', [
            'success' => $result['success'],
            'output' => $result['output'] ?? '',
            'error' => $result['error'] ?? null,
        ]);

        if ($result['success'] && ! empty(trim($result['output'] ?? ''))) {
            $newDirectory = trim($result['output']);
            $previousDirectory = $this->currentDirectory;
            $this->currentDirectory = $newDirectory;
            // Show the new directory
            $this->addToOutput("Changed to: {$newDirectory}", 'info');

            Log::info('[SSH Console] Directory changed', [
                'server_id' => $this->server->id,
                'from' => $previousDirectory,
                'to' => $newDirectory,
            ]);
        } else {
            // Get error from either error field or output field
            $error = ! empty($result['error']) ? $result['error'] : ($result['output'] ?? 'Directory not found or permission denied');
            if (empty(trim($error))) {
                $error = 'Directory not found or permission denied';
            }
            $this->addToOutput($error, 'error');

            Log::warning('[SSH Console] CD command failed', [
                'server_id' => $this->server->id,
                'target_path' => $newPath,
                'error' => $error,
            ]);
        }

        $this->isLoading = false;
    }

    private function runCommand(string $command): void
    {
        $this->isLoading = true;

        $ssh = app(SSHManager::class);

        // Escape the current directory path properly
        $escapedDir = escapeshellarg($this->currentDirectory);

        // Prepend cd to current directory for each command
        $fullCommand = "cd {$escapedDir} 2>/dev/null && {$command}";

        Log::debug('[SSH Console] Running command', [
            'server_id' => $this->server->id,
            'user_command' => $command,
            'full_command' => $fullCommand,
            'current_directory' => $this->currentDirectory,
        ]);

        $result = $ssh->executeCommand($this->server, $fullCommand);

        Log::debug('[SSH Console] Command result', [
            'server_id' => $this->server->id,
            'command' => $command,
            'success' => $result['success'],
            'output_length' => strlen($result['output'] ?? ''),
            'has_error' => ! empty($result['error']),
            'exit_code' => $result['exit_code'] ?? null,
        ]);

        // In a real terminal, output is shown regardless of exit code
        // First, show stdout if present
        $output = $result['output'] ?? '';
        $hasOutput = ! empty(trim($output));

        if ($hasOutput) {
            // Determine output type based on success status
            $outputType = $result['success'] ? 'output' : 'error';
            $this->addToOutput($output, $outputType);
        }

        // Then show stderr if present and different from stdout
        $error = $result['error'] ?? '';
        if (! empty(trim($error)) && $error !== $output) {
            $this->addToOutput($error, 'error');
        }

        // Log the result
        if ($result['success']) {
            Log::info('[SSH Console] Command executed successfully', [
                'server_id' => $this->server->id,
                'command' => $command,
                'output_preview' => $hasOutput ? substr($output, 0, 200) : '(no output)',
            ]);
        } else {
            $exitCode = $result['exit_code'] ?? 'unknown';

            Log::error('[SSH Console] Command failed', [
                'server_id' => $this->server->id,
                'command' => $command,
                'exit_code' => $exitCode,
                'output_preview' => $hasOutput ? substr($output, 0, 200) : '(no output)',
                'error_preview' => ! empty(trim($error)) ? substr($error, 0, 200) : '(no error)',
            ]);

            // Show exit code for failed commands (if not already obvious)
            if (! $hasOutput && empty(trim($error))) {
                $this->addToOutput("Command exited with code: {$exitCode}", 'error');
            }
        }

        $this->isLoading = false;
    }

    /**
     * Tab completion - get suggestions from server
     */
    public function tabComplete(): void
    {
        if (! $this->connected) {
            Log::debug('[SSH Console] Tab complete skipped - not connected');

            return;
        }

        Log::debug('[SSH Console] Tab completion triggered', [
            'server_id' => $this->server->id,
            'current_input' => $this->command,
            'current_directory' => $this->currentDirectory,
        ]);

        $ssh = app(SSHManager::class);

        // Parse the command to find what we're completing
        $commandText = $this->command ?? '';
        $parts = preg_split('/\s+/', $commandText, -1, PREG_SPLIT_NO_EMPTY);
        $lastPart = ! empty($parts) ? end($parts) : '';
        $isFirstWord = count($parts) <= 1 && ! str_ends_with($commandText, ' ');

        // If command is empty or ends with space, list current directory contents
        if (empty($lastPart) || str_ends_with($commandText, ' ')) {
            $escapedDir = escapeshellarg($this->currentDirectory);
            $result = $ssh->executeCommand(
                $this->server,
                "cd {$escapedDir} && ls -1 2>/dev/null | head -30"
            );

            if ($result['success'] && ! empty($result['output'])) {
                $completions = array_filter(explode("\n", trim($result['output'])));
                if (! empty($completions)) {
                    $this->tabCompletions = $completions;
                    $this->addToOutput(implode('  ', $completions), 'info');
                }
            }
            $this->dispatch('focusInput');

            return;
        }

        if ($isFirstWord) {
            // Complete command names using type -a or which fallback
            $escapedPart = addslashes($lastPart);
            $result = $ssh->executeCommand(
                $this->server,
                "bash -c 'compgen -c \"$escapedPart\"' 2>/dev/null | sort -u | head -20"
            );

            // Fallback to PATH search if compgen fails
            if (! $result['success'] || empty(trim($result['output'] ?? ''))) {
                $result = $ssh->executeCommand(
                    $this->server,
                    "ls /usr/bin /bin /usr/local/bin 2>/dev/null | grep -i '^{$escapedPart}' | sort -u | head -20"
                );
            }
        } else {
            // Complete file/directory names
            $searchPattern = $lastPart;

            // Handle paths with directories
            $dirname = dirname($lastPart);
            $basename = basename($lastPart);

            if ($dirname !== '.' && $dirname !== $lastPart) {
                // User is typing a path like "logs/acc" - complete in that subdirectory
                $searchDir = $dirname;
                $searchPattern = $basename;
            } else {
                $searchDir = '.';
                $searchPattern = $lastPart;
            }

            $escapedPattern = addslashes($searchPattern);
            $escapedSearchDir = addslashes($searchDir);
            $escapedCurrentDir = escapeshellarg($this->currentDirectory);

            $result = $ssh->executeCommand(
                $this->server,
                "cd {$escapedCurrentDir} && cd \"{$escapedSearchDir}\" 2>/dev/null && ls -1d {$escapedPattern}* 2>/dev/null | head -30"
            );
        }

        $output = trim($result['output'] ?? '');

        if ($result['success'] && ! empty($output)) {
            $completions = array_filter(explode("\n", $output));

            if (count($completions) === 1) {
                // Single match - complete it
                $completion = $completions[0];
                if ($isFirstWord) {
                    $this->command = $completion.' ';
                } else {
                    // Preserve the path prefix if the user was typing a path
                    $dirname = dirname($lastPart);
                    if ($dirname !== '.' && $dirname !== $lastPart) {
                        $completion = $dirname.'/'.$completion;
                    }

                    array_pop($parts);
                    $parts[] = $completion;
                    $this->command = implode(' ', $parts);

                    // Check if it's a directory to add trailing slash
                    $checkPath = ($dirname !== '.' && $dirname !== $lastPart)
                        ? "{$this->currentDirectory}/{$dirname}/".basename($completion)
                        : "{$this->currentDirectory}/".$completion;

                    $isDir = $ssh->executeCommand(
                        $this->server,
                        'test -d '.escapeshellarg($checkPath)." && echo 'dir'"
                    );
                    if ($isDir['success'] && trim($isDir['output'] ?? '') === 'dir') {
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
                if (strlen($commonPrefix) > strlen(basename($lastPart))) {
                    // Preserve path prefix
                    $dirname = dirname($lastPart);
                    if ($dirname !== '.' && $dirname !== $lastPart) {
                        $commonPrefix = $dirname.'/'.$commonPrefix;
                    }

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
        } else {
            // No matches found
            $this->addToOutput('No completions found', 'info');

            Log::debug('[SSH Console] No tab completions found', [
                'server_id' => $this->server->id,
                'search_term' => $lastPart ?? '',
            ]);
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
            $this->addToOutput('Interactive session started (ID: '.$this->sessionId.')', 'success');
        } else {
            $this->addToOutput('Failed to start interactive session: '.$session['error'], 'error');
        }
    }

    public function clearOutput(): void
    {
        $this->output = [];
        $this->addToOutput('Terminal cleared', 'info');
    }

    public function disconnect(): void
    {
        Log::info('[SSH Console] Disconnecting', [
            'server_id' => $this->server->id,
            'server_name' => $this->server->name,
            'session_id' => $this->sessionId,
        ]);

        // Close SSH connection
        $ssh = app(SSHManager::class);
        $ssh->closeConnection($this->server);

        if ($this->sessionId) {
            $whispBridge = app(WhispBridge::class);
            $whispBridge->closeSession($this->sessionId);
            $this->sessionId = null;
        }

        $this->connected = false;
        $this->addToOutput('Disconnected from '.$this->server->getConnectionString(), 'info');

        Log::info('[SSH Console] Disconnected successfully', [
            'server_id' => $this->server->id,
        ]);
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
            Log::debug('[SSH Console] Skipping empty output line');

            return;
        }

        $outputEntry = [
            'text' => $text,
            'type' => $type,
            'timestamp' => now()->format('H:i:s'),
        ];

        // Directly append to array
        $this->output[] = $outputEntry;

        // Keep only last 1000 lines
        if (count($this->output) > 1000) {
            $this->output = array_values(array_slice($this->output, -1000));
        }

        // Increment counter to force Livewire to detect change
        $this->outputCount++;

        Log::debug('[SSH Console] Added to output', [
            'type' => $type,
            'text_length' => strlen($text),
            'text_preview' => substr($text, 0, 100),
            'total_output_lines' => count($this->output),
            'output_count' => $this->outputCount,
        ]);

        // Dispatch event with the output data directly to JavaScript
        $this->dispatch('output-added', [
            'output' => $outputEntry,
            'count' => $this->outputCount,
        ]);
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
            $dir = '~'.substr($dir, strlen($home));
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

    public function toggleTheme(): void
    {
        $themes = ['modern', 'retro-green', 'retro-amber', 'cyberpunk'];
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
