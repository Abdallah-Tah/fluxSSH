<?php

namespace App\Livewire;

use App\Models\ActivityLog;
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
    }

    public function render()
    {
        $query = ActivityLog::query()
            ->with(['user', 'server'])
            ->where('user_id', auth()->id())
            ->latest();

        if ($this->serverId) {
            $query->where('server_id', $this->serverId);
        }

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $activities = $query->paginate($this->limit);

        return view('livewire.activity-feed', [
            'activities' => $activities,
        ]);
    }
}
