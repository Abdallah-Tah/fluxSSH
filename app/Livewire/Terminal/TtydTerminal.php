<?php

namespace App\Livewire\Terminal;

use App\Models\Server;
use App\Services\Terminal\TtydManager;
use Livewire\Component;

class TtydTerminal extends Component
{
    public Server $server;

    public string $theme = 'saturn';

    public ?int $port = null;

    public bool $connected = false;

    public ?string $error = null;

    public function mount(Server $server): void
    {
        $this->server = $server;
        $this->startTerminal();
    }

    public function startTerminal(): void
    {
        $ttydManager = app(TtydManager::class);
        $result = $ttydManager->startSession($this->server);

        if ($result['success']) {
            $this->port = $result['port'];
            $this->connected = true;
            $this->error = null;
        } else {
            $this->error = $result['error'] ?? 'Failed to start terminal';
            $this->connected = false;
        }
    }

    public function reconnect(): void
    {
        $this->error = null;
        $this->connected = false;
        $this->startTerminal();
    }

    public function toggleTheme(): void
    {
        $themes = ['saturn', 'dracula', 'github-dark'];
        $currentIndex = array_search($this->theme, $themes);
        $this->theme = $themes[($currentIndex + 1) % count($themes)];
    }

    public function render()
    {
        return view('livewire.terminal.ttyd-terminal');
    }
}
