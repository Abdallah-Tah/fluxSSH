<?php

namespace App\Livewire\Server;

use App\Models\Server;
use App\Services\SSH\SSHManager;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public Server $server;

    public bool $isLoading = false;

    public bool $hasError = false;

    public ?string $errorMessage = null;

    public array $stats = [];

    public function mount(Server $server): void
    {
        $this->server = $server;
        $this->refreshStats();
    }

    #[On('refresh-stats')]
    public function refreshStats(): void
    {
        if ($this->isLoading) {
            return;
        }

        $this->isLoading = true;
        $this->hasError = false;
        $this->errorMessage = null;

        try {
            $sshManager = app(SSHManager::class);
            $this->stats = $sshManager->getServerStats($this->server);

            if (! $this->stats['success']) {
                $this->hasError = true;
                $this->errorMessage = $this->stats['error'] ?? 'Failed to fetch server statistics';
            }

            // Refresh server model to get updated data
            $this->server->refresh();
        } catch (\Exception $e) {
            $this->hasError = true;
            $this->errorMessage = 'Connection failed: '.$e->getMessage();
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.server.show');
    }
}
