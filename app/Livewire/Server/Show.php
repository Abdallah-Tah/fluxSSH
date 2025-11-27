<?php

namespace App\Livewire\Server;

use App\Models\ActivityLog;
use App\Models\CommandHistory;
use App\Models\Server;
use App\Services\SSH\SSHManager;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public Server $server;

    public bool $isLoading = false;

    public bool $hasError = false;

    public ?string $errorMessage = null;

    public array $stats = [];

    public string $activeTab = 'overview';

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

    public function setTab(string $tab): void
    {
        if (! in_array($tab, ['overview', 'logs', 'activity', 'settings'], true)) {
            return;
        }

        $this->activeTab = $tab;

        if ($tab === 'overview') {
            $this->refreshStats();
        }
    }

    public function getCommandLogsProperty(): Collection
    {
        return CommandHistory::query()
            ->where('user_id', auth()->id())
            ->where('server_id', $this->server->id)
            ->latest()
            ->limit(15)
            ->get();
    }

    public function getActivityLogEntriesProperty(): Collection
    {
        return ActivityLog::query()
            ->with('user')
            ->where('user_id', auth()->id())
            ->where('server_id', $this->server->id)
            ->latest()
            ->limit(15)
            ->get();
    }

    public function render(): View
    {
        return view('livewire.server.show');
    }
}
