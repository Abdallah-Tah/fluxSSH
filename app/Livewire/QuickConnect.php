<?php

namespace App\Livewire;

use App\Models\Server;
use App\Services\SSHService;
use Livewire\Component;
use Livewire\Attributes\On;

class QuickConnect extends Component
{
    public $recentServers = [];
    public $isConnecting = false;
    public $selectedServerId = null;

    public function mount(): void
    {
        $this->loadRecentServers();
    }

    public function loadRecentServers(): void
    {
        $this->recentServers = Server::where('is_active', true)
            ->orderBy('last_connected_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($server) => [
                'id' => $server->id,
                'name' => $server->name,
                'host' => $server->host,
                'last_connected' => $server->last_connected_at?->diffForHumans() ?? 'Never',
                'status' => $server->is_active ? 'active' : 'inactive'
            ])
            ->toArray();
    }

    public function quickConnect(int $serverId): void
    {
        $this->selectedServerId = $serverId;
        $this->isConnecting = true;

        $server = Server::findOrFail($serverId);

        try {
            $sshService = new SSHService();
            $result = $sshService->testConnection($server);

            if ($result['success']) {
                // Update last connected timestamp
                $server->update(['last_connected_at' => now()]);

                // Redirect to console
                $this->redirect(route('console', $server), navigate: true);
            } else {
                session()->flash('error', 'Failed to connect: ' . $result['error']);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Connection failed: ' . $e->getMessage());
        }

        $this->isConnecting = false;
        $this->selectedServerId = null;
    }

    #[On('server-updated')]
    public function refreshServers(): void
    {
        $this->loadRecentServers();
    }

    public function render()
    {
        return view('livewire.quick-connect');
    }
}
