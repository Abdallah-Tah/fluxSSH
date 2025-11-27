<div class="space-y-4">
    {{-- Filter Tabs --}}
    <div class="flex gap-2 border-b border-zinc-700/50 pb-2">
        <button
            wire:click="setFilter('all')"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $filterType === 'all' ? 'bg-zinc-700 text-white' : 'text-zinc-400 hover:text-zinc-200' }}">
            All Activity
        </button>
        <button
            wire:click="setFilter('connection')"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $filterType === 'connection' ? 'bg-zinc-700 text-white' : 'text-zinc-400 hover:text-zinc-200' }}">
            Connections
        </button>
        <button
            wire:click="setFilter('command')"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $filterType === 'command' ? 'bg-zinc-700 text-white' : 'text-zinc-400 hover:text-zinc-200' }}">
            Commands
        </button>
        <button
            wire:click="setFilter('error')"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $filterType === 'error' ? 'bg-zinc-700 text-white' : 'text-zinc-400 hover:text-zinc-200' }}">
            Errors
        </button>
    </div>

    {{-- Activity List --}}
    <div class="space-y-2">
        @forelse($activities as $activity)
            <div class="flex items-start gap-3 p-3 rounded-lg bg-zinc-800/50 hover:bg-zinc-800 transition-colors border border-zinc-700/50">
                {{-- Icon based on type --}}
                <div class="flex-shrink-0 mt-0.5">
                    @if($activity->type === 'connection')
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    @elseif($activity->type === 'command')
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @elseif($activity->type === 'error')
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-zinc-200 font-medium">{{ $activity->description }}</p>

                    <div class="flex items-center gap-3 mt-1 text-xs text-zinc-500">
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
                        <div class="mt-2 text-xs text-zinc-400 font-mono bg-zinc-900/50 p-2 rounded border border-zinc-700/30">
                            @foreach($activity->metadata as $key => $value)
                                <div><span class="text-zinc-500">{{ $key }}:</span> {{ is_array($value) ? json_encode($value) : $value }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Action badge --}}
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded
                        @if($activity->action === 'connected' || $activity->action === 'success') bg-emerald-900/30 text-emerald-400
                        @elseif($activity->action === 'failed' || $activity->action === 'error') bg-red-900/30 text-red-400
                        @else bg-zinc-700/30 text-zinc-400
                        @endif
                    ">
                        {{ ucfirst($activity->action) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-zinc-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
