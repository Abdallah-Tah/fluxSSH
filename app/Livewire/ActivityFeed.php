<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityFeed extends Component
{
    use WithPagination;

    public ?int $serverId = null;

    public string $filterType = 'all';

    public int $limit = 10;

    public function mount(?int $serverId = null): void
    {
        $this->serverId = $serverId;
    }

    public function setFilter(string $type): void
    {
        $this->filterType = $type;
        $this->resetPage();
        $this->clearCache();
    }

    /**
     * Listen for dashboard refresh events
     */
    #[On('dashboard-refreshed')]
    public function handleDashboardRefresh(): void
    {
        $this->clearCache();
        unset($this->activities);
    }

    /**
     * Cached activities with 5-minute cache
     */
    #[Computed(cache: true, seconds: 300)]
    public function activities()
    {
        $cacheKey = sprintf(
            'activity_feed.%s.%s.%s.%d',
            auth()->id(),
            $this->serverId ?? 'all',
            $this->filterType,
            $this->getPage()
        );

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            function () {
                $query = ActivityLog::query()
                    ->select(['id', 'user_id', 'server_id', 'type', 'action', 'description', 'metadata', 'created_at'])
                    ->where('user_id', auth()->id())
                    ->latest();

                if ($this->serverId) {
                    $query->where('server_id', $this->serverId);
                }

                if ($this->filterType !== 'all') {
                    $query->where('type', $this->filterType);
                }

                return $query->paginate($this->limit);
            }
        );
    }

    /**
     * Clear the activity cache
     */
    private function clearCache(): void
    {
        $cacheKey = sprintf(
            'activity_feed.%s.%s.%s.%d',
            auth()->id(),
            $this->serverId ?? 'all',
            $this->filterType,
            $this->getPage()
        );

        Cache::forget($cacheKey);
    }

    public function render()
    {
        return view('livewire.activity-feed', [
            'activities' => $this->activities,
        ]);
    }
}
