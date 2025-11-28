<?php

namespace App\Livewire\Terminal;

use App\Models\Server;
use App\Services\Terminal\TerminalSession;
use Illuminate\Support\Str;
use Livewire\Component;

class InteractiveTerminal extends Component
{
    public Server $server;

    public string $sessionId;

    public bool $connected = false;

    private ?TerminalSession $session = null;

    public function mount(Server $server): void
    {
        $this->server = $server;
        $this->sessionId = Str::uuid()->toString();
    }

    public function connect(): void
    {
        $this->session = new TerminalSession($this->server, $this->sessionId);
        $result = $this->session->connect();

        if ($result['success']) {
            $this->connected = true;
            $this->dispatch('terminal-connected', sessionId: $this->sessionId);
        } else {
            $this->dispatch('terminal-error', error: $result['error'] ?? 'Connection failed');
        }
    }

    public function sendData(string $data): void
    {
        if ($this->session && $this->session->isActive()) {
            $this->session->write($data);
            $output = $this->session->read();
            $this->dispatch('terminal-output', output: $output);
        }
    }

    public function resize(int $cols, int $rows): void
    {
        if ($this->session && $this->session->isActive()) {
            $this->session->resize($cols, $rows);
        }
    }

    public function disconnect(): void
    {
        if ($this->session) {
            $this->session->disconnect();
        }

        $this->connected = false;
    }

    public function render()
    {
        return view('livewire.terminal.interactive-terminal');
    }
}
