<?php

namespace App\Livewire;

use App\Models\CommandHistory;
use App\Models\Server;
use Livewire\Component;

class Dashboard extends Component
{
    public string $search = '';

    public function getServersProperty()
    {
        return Server::query()
            ->where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('host', 'like', "%{$this->search}%");
                });
            })
            ->with(['commandHistories' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->withCount('commandHistories')
            ->latest()
            ->get()
            ->map(function ($server) {
                $server->status_color = $this->getServerStatusColor($server);
                $server->uptime_text = $this->getServerUptimeText($server);

                return $server;
            });
    }

    public function getTotalServersProperty()
    {
        return Server::where('user_id', auth()->id())->count();
    }

    public function getActiveServersProperty()
    {
        return Server::where('user_id', auth()->id())
            ->where('is_active', true)
            ->count();
    }

    public function getTotalCommandsProperty()
    {
        return CommandHistory::where('user_id', auth()->id())->count();
    }

    public function getRecentActivityProperty()
    {
        return CommandHistory::query()
            ->where('user_id', auth()->id())
            ->with('server')
            ->latest()
            ->limit(5)
            ->get();
    }

    private function getServerStatusColor(Server $server): string
    {
        if (! $server->is_active) {
            return 'zinc';
        }

        if ($server->last_connected_at && $server->last_connected_at->diffInMinutes(now()) < 30) {
            return 'emerald';
        }

        if ($server->last_connected_at && $server->last_connected_at->diffInHours(now()) < 24) {
            return 'yellow';
        }

        return 'red';
    }

    private function getServerUptimeText(Server $server): string
    {
        if (! $server->last_connected_at) {
            return 'Never connected';
        }

        return $server->last_connected_at->diffForHumans();
    }

    public function connectToServer(int $serverId): void
    {
        $this->redirect(route('console', ['server' => $serverId]));
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
