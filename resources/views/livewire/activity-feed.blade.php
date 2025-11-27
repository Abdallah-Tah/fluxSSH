<div class="space-y-4">
    {{-- Filter Tabs --}}
    <div class="flex gap-2 border-b border-border-subtle pb-2">
        <button
            wire:click="setFilter('all')"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $filterType === 'all' ? 'bg-primary-subtle text-primary-600' : 'text-text-secondary hover:text-text-primary hover:bg-bg-surface-alt' }}">
            All Activity
        </button>
        <button
            wire:click="setFilter('connection')"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $filterType === 'connection' ? 'bg-primary-subtle text-primary-600' : 'text-text-secondary hover:text-text-primary hover:bg-bg-surface-alt' }}">
            Connections
        </button>
        <button
            wire:click="setFilter('command')"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $filterType === 'command' ? 'bg-primary-subtle text-primary-600' : 'text-text-secondary hover:text-text-primary hover:bg-bg-surface-alt' }}">
            Commands
        </button>
        <button
            wire:click="setFilter('error')"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $filterType === 'error' ? 'bg-primary-subtle text-primary-600' : 'text-text-secondary hover:text-text-primary hover:bg-bg-surface-alt' }}">
            Errors
        </button>
    </div>

    {{-- Activity List --}}
    <div class="space-y-2">
        @forelse($activities as $activity)
            <div class="flex items-start gap-3 p-3 rounded-lg bg-bg-surface hover:bg-bg-surface-alt transition-colors border border-border-subtle">
                {{-- Icon based on type --}}
                <div class="flex-shrink-0 mt-0.5">
                    @if($activity->type === 'connection')
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    @elseif($activity->type === 'command')
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @elseif($activity->type === 'error')
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-text-primary font-medium">{{ $activity->description }}</p>

                    <div class="flex items-center gap-3 mt-1 text-xs text-text-tertiary">
                        <span>{{ $activity->created_at->diffForHumans() }}</span>

                        @if($activity->server)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                                </svg>
                                {{ $activity->server->name }}
                            </span>
                        @endif

                        @if($activity->ip_address)
                            <span>{{ $activity->ip_address }}</span>
                        @endif
                    </div>

                    @if($activity->metadata)
                        <div class="mt-2 text-xs text-text-secondary font-mono bg-bg-surface-alt p-2 rounded border border-border-subtle">
                            @foreach($activity->metadata as $key => $value)
                                <div><span class="text-text-tertiary">{{ $key }}:</span> {{ is_array($value) ? json_encode($value) : $value }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Action badge --}}
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded
                        @if($activity->action === 'connected' || $activity->action === 'success') bg-emerald-500/10 text-emerald-600
                        @elseif($activity->action === 'failed' || $activity->action === 'error') bg-red-500/10 text-red-600
                        @else bg-bg-surface-alt text-text-secondary
                        @endif
                    ">
                        {{ ucfirst($activity->action) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-text-tertiary">
                <svg class="w-12 h-12 mx-auto mb-3 text-text-tertiary/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">No activity to display</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($activities->hasPages())
        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    @endif
</div>
