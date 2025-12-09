<?php

namespace App\Livewire\Terminal;

use App\Models\Server;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class InteractiveTerminal extends Component
{
    public Server $server;

    public string $sessionId = '';

    public bool $connected = false;

    public string $error = '';

    public string $currentDirectory = '~';

    public array $commandHistory = [];

    public int $historyIndex = -1;

    public function mount(Server $server): void
    {
        $this->server = $server;
        $this->sessionId = 'term_'.Str::uuid()->toString();
    }

    public function connect(): void
    {
        try {
            // Test connection
            $ssh = $this->createConnection();

            if (! $ssh) {
                $this->error = 'Failed to create SSH connection';
                $this->dispatch('terminal-error', error: $this->error);

                return;
            }

            // Get initial info
            $hostname = trim($ssh->exec('hostname') ?: 'server');
            $user = trim($ssh->exec('whoami') ?: $this->server->username);
            $cwd = trim($ssh->exec('pwd') ?: '~');

            $ssh->disconnect();

            $this->connected = true;
            $this->error = '';
            $this->currentDirectory = $cwd;

            // Update server
            $this->server->update(['last_connected_at' => now()]);

            // Send welcome message
            $welcome = "\r\n\033[1;32m✓ Connected to {$this->server->name}\033[0m\r\n";
            $welcome .= "\033[90mHost: {$this->server->host} | User: {$user}\033[0m\r\n\r\n";
            $welcome .= $this->getPrompt($user, $hostname, $cwd);

            $this->dispatch('terminal-output', output: $welcome);
            $this->dispatch('terminal-connected');

            Log::info('[InteractiveTerminal] Connected', ['session_id' => $this->sessionId]);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->dispatch('terminal-error', error: $this->error);
            Log::error('[InteractiveTerminal] Connection error', ['error' => $e->getMessage()]);
        }
    }

    public function executeCommand(string $command): void
    {
        if (! $this->connected || empty(trim($command))) {
            return;
        }

        $command = trim($command);

        // Add to history
        if ($command && (empty($this->commandHistory) || end($this->commandHistory) !== $command)) {
            $this->commandHistory[] = $command;
            if (count($this->commandHistory) > 100) {
                array_shift($this->commandHistory);
            }
        }
        $this->historyIndex = count($this->commandHistory);

        try {
            $ssh = $this->createConnection();

            if (! $ssh) {
                $this->dispatch('terminal-output', output: "\r\n\033[1;31mConnection lost. Please reconnect.\033[0m\r\n");
                $this->connected = false;
                $this->dispatch('terminal-disconnected');

                return;
            }

            // Check if command requires interactive terminal (PTY)
            $interactiveCommands = ['htop', 'top', 'vim', 'vi', 'nano', 'less', 'more'];
            $requiresPTY = false;
            foreach ($interactiveCommands as $cmd) {
                if (str_starts_with($command, $cmd)) {
                    $requiresPTY = true;
                    break;
                }
            }

            // Handle cd command specially
            if (preg_match('/^cd\s*(.*)$/', $command, $matches)) {
                $dir = trim($matches[1]) ?: '~';
                $output = $ssh->exec("cd {$this->currentDirectory} && cd {$dir} && pwd 2>&1");
                $newDir = trim($output);

                if ($newDir && ! str_contains($newDir, 'No such file') && ! str_contains($newDir, 'Permission denied')) {
                    $this->currentDirectory = $newDir;
                    $output = '';
                } else {
                    $output = $newDir;
                }
            } elseif ($requiresPTY) {
                // Interactive commands require ttyd terminal
                $ttydUrl = route('terminal.ttyd', $this->server);
                $output = "\033[1;33mInteractive command '{$command}' requires a full terminal.\033[0m\r\n";
                $output .= "\033[1;36mSwitch to Professional Terminal for full interactive support:\033[0m\r\n";
                $output .= "\033[1;32m→ {$ttydUrl}\033[0m\r\n\r\n";
                $output .= "The Professional Terminal supports:\r\n";
                $output .= "  • htop, top - interactive process monitoring\r\n";
                $output .= "  • vim, nano - text editing\r\n";
                $output .= "  • less, more - file paging\r\n";
                $output .= "  • Any other interactive command\r\n\r\n";
                $output .= "Alternative for this terminal:\r\n";
                $output .= "  - Instead of 'htop', try: 'ps aux' or 'top -bn1 | head -20'\r\n";
                $output .= "  - Instead of 'vim/nano', try: 'cat filename'\r\n";
            } else {
                // Execute command in current directory
                $output = $ssh->exec("cd {$this->currentDirectory} && {$command} 2>&1");
            }

            // Get updated directory and prompt info (reuse connection)
            $user = trim($ssh->exec('whoami') ?: $this->server->username);
            $hostname = trim($ssh->exec('hostname') ?: 'server');

            $ssh->disconnect();

            // Send output
            $result = '';
            if (! empty($output)) {
                $result .= $output;
                if (! str_ends_with($output, "\n")) {
                    $result .= "\r\n";
                }
            }
            $result .= $this->getPrompt($user, $hostname, $this->currentDirectory);

            $this->dispatch('terminal-output', output: $result);
        } catch (\Exception $e) {
            Log::error('[InteractiveTerminal] Command error', ['error' => $e->getMessage()]);
            $this->dispatch('terminal-output', output: "\r\n\033[1;31mError: {$e->getMessage()}\033[0m\r\n");
        }
    }

    private function getPrompt(string $user, string $hostname, string $cwd): string
    {
        // Shorten home directory
        $homeDir = "/home/{$user}";
        $displayCwd = $cwd;
        if (str_starts_with($cwd, $homeDir)) {
            $displayCwd = '~'.substr($cwd, strlen($homeDir));
        } elseif ($cwd === '/root') {
            $displayCwd = '~';
        }

        // Colored prompt like: user@hostname:~/path$
        return "\033[1;32m{$user}@{$hostname}\033[0m:\033[1;34m{$displayCwd}\033[0m$ ";
    }

    private function createConnection(): ?SSH2
    {
        try {
            $ssh = new SSH2($this->server->host, $this->server->port, 10);

            if ($this->server->auth_type === 'key') {
                $key = PublicKeyLoader::load($this->server->private_key);
                $authenticated = $ssh->login($this->server->username, $key);
            } else {
                $authenticated = $ssh->login($this->server->username, $this->server->password);
            }

            if (! $authenticated) {
                return null;
            }

            return $ssh;
        } catch (\Exception $e) {
            Log::error('[InteractiveTerminal] SSH connection error', ['error' => $e->getMessage()]);

            return null;
        }
    }

    public function disconnect(): void
    {
        $this->connected = false;
        $this->dispatch('terminal-disconnected');
        Log::info('[InteractiveTerminal] Disconnected', ['session_id' => $this->sessionId]);
    }

    public function render()
    {
        return view('livewire.terminal.interactive-terminal');
    }
}
