<?php

namespace App\Livewire;

use App\Models\CommandHistory;
use App\Models\Server;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    public string $search = '';

    public bool $autoRefresh = false;

    public int $refreshInterval = 300000; // 5 minutes (300 seconds)

    public function toJSON(): array
    {
        return [
            'servers' => $this->servers()->toArray(),
            'stats' => [
                'total' => $this->totalServers(),
                'active' => $this->activeServers(),
                'commands' => $this->totalCommands(),
            ],
        ];
    }

    /**
     * Livewire event listeners for NativePHP events
     */
    protected $listeners = [
        'activity-created' => 'handleActivityCreated',
        'server-status-changed' => 'handleServerStatusChanged',
    ];

    /**
     * Handle activity created event from NativePHP
     */
    public function handleActivityCreated(): void
    {
        $this->clearCaches();
    }

    /**
     * Handle server status changed event from NativePHP
     */
    public function handleServerStatusChanged(): void
    {
        $this->clearCaches();
    }

    /**
     * Clear all dashboard caches
     */
    private function clearCaches(): void
    {
        Cache::forget('dashboard.servers.'.auth()->id().".{$this->search}");
        Cache::forget('dashboard.stats.total_servers.'.auth()->id());
        Cache::forget('dashboard.stats.active_servers.'.auth()->id());
        Cache::forget('dashboard.stats.total_commands.'.auth()->id());

        // Unset computed properties to force reload
        unset($this->servers);
        unset($this->totalServers);
        unset($this->activeServers);
        unset($this->totalCommands);
    }

    /**
     * Cached servers property with 5-minute cache
     */
    #[Computed(cache: true, seconds: 300)]
    public function servers()
    {
        return Cache::remember(
            'dashboard.servers.'.auth()->id().".{$this->search}",
            now()->addMinutes(5),
            fn () => Server::query()
                ->where('user_id', auth()->id())
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', "%{$this->search}%")
                            ->orWhere('host', 'like', "%{$this->search}%");
                    });
                })
                ->select(['id', 'name', 'host', 'port', 'is_active', 'last_connected_at', 'user_id'])
                ->latest()
                ->get()
        );
    }

    /**
     * Cached total servers count with 10-minute cache
     */
    #[Computed(cache: true, seconds: 600)]
    public function totalServers()
    {
        return Cache::remember(
            'dashboard.stats.total_servers.'.auth()->id(),
            now()->addMinutes(10),
            fn () => Server::where('user_id', auth()->id())->count()
        );
    }

    /**
     * Cached active servers count with 5-minute cache
     */
    #[Computed(cache: true, seconds: 300)]
    public function activeServers()
    {
        return Cache::remember(
            'dashboard.stats.active_servers.'.auth()->id(),
            now()->addMinutes(5),
            fn () => Server::where('user_id', auth()->id())
                ->where('is_active', true)
                ->count()
        );
    }

    /**
     * Cached total commands count with 10-minute cache
     */
    #[Computed(cache: true, seconds: 600)]
    public function totalCommands()
    {
        return Cache::remember(
            'dashboard.stats.total_commands.'.auth()->id(),
            now()->addMinutes(10),
            fn () => CommandHistory::where('user_id', auth()->id())->count()
        );
    }

    /**
     * Toggle auto-refresh on/off
     */
    public function toggleAutoRefresh(): void
    {
        $this->autoRefresh = ! $this->autoRefresh;
        $this->dispatch('auto-refresh-toggled');
    }

    /**
     * Manual refresh - clears cache and reloads
     */
    public function refresh(): void
    {
        // Clear all dashboard caches for this user
        Cache::forget('dashboard.servers.'.auth()->id().".{$this->search}");
        Cache::forget('dashboard.stats.total_servers.'.auth()->id());
        Cache::forget('dashboard.stats.active_servers.'.auth()->id());
        Cache::forget('dashboard.stats.total_commands.'.auth()->id());

        // Unset computed properties to force reload
        unset($this->servers);
        unset($this->totalServers);
        unset($this->activeServers);
        unset($this->totalCommands);

        // Dispatch event to refresh child components
        $this->dispatch('dashboard-refreshed');
    }

    /**
     * Update refresh interval
     */
    public function setRefreshInterval(int $interval): void
    {
        $this->refreshInterval = max(10000, min(300000, $interval)); // 10s to 5min
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
