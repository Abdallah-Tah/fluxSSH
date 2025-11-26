<div>
    @if (count($recentServers) > 0)
        <div class="space-y-3">
            @foreach ($recentServers as $server)
                <div
                    class="group relative flex items-center justify-between p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 hover:bg-zinc-100 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600 transition-all duration-200 {{ $isConnecting && $selectedServerId === $server['id'] ? 'ring-2 ring-emerald-500 dark:ring-emerald-400' : '' }}">

                    <!-- Server Info -->
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div
                            class="w-11 h-11 rounded-xl {{ $server['status'] === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-zinc-200 dark:bg-zinc-700' }} flex items-center justify-center shrink-0 transition-colors">
                            <svg class="w-5 h-5 {{ $server['status'] === 'active' ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400' }}"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate">
                                    {{ $server['name'] }}
                                </p>
                                @if($server['status'] === 'active')
                                    <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                                        Active
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate font-mono mt-0.5">
                                {{ $server['host'] }}
                            </p>

                            <!-- Last Connected Time -->
                            @if($server['last_connected'] !== 'Never')
                                <div class="flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-[10px] text-zinc-400 dark:text-zinc-500">
                                        {{ $server['last_connected'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Connect Button -->
                    <flux:button
                        wire:click="quickConnect({{ $server['id'] }})"
                        wire:loading.attr="disabled"
                        wire:target="quickConnect({{ $server['id'] }})"
                        variant="filled"
                        size="sm"
                        class="shrink-0 ml-3"
                    >
                        <span wire:loading.remove wire:target="quickConnect({{ $server['id'] }})">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z" />
                            </svg>
                        </span>
                        <span wire:loading wire:target="quickConnect({{ $server['id'] }})">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                    </flux:button>

                    <!-- Connecting Overlay -->
                    @if($isConnecting && $selectedServerId === $server['id'])
                        <div class="absolute inset-0 bg-emerald-500/10 dark:bg-emerald-400/10 rounded-xl flex items-center justify-center pointer-events-none">
                            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white dark:bg-zinc-900 border border-emerald-500 dark:border-emerald-400 shadow-lg">
                                <svg class="w-4 h-4 animate-spin text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-300">Connecting...</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <flux:button href="{{ route('servers') }}" variant="ghost" class="w-full">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    View All Servers
                </div>
            </flux:button>
        </div>
    @else
        <div class="text-center py-10">
            <div
                class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                </svg>
            </div>
            <flux:heading size="lg" class="mb-2">No servers yet</flux:heading>
            <flux:text class="text-zinc-500 dark:text-zinc-400 mb-6">
                Add your first server to get started with SSH connections
            </flux:text>
            <flux:button href="{{ route('servers') }}" variant="primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Your First Server
            </flux:button>
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <flux:callout variant="danger" class="mt-4">
            {{ session('error') }}
        </flux:callout>
    @endif
</div>
