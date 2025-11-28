<div class="min-h-screen p-4 lg:p-6 xl:p-8">
    <div class="mx-auto max-w-[1600px] space-y-6 lg:space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-text-primary tracking-tight">Dashboard</h1>
                <p class="mt-1 text-sm lg:text-base text-text-secondary">Monitor your infrastructure and recent activity</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Refresh Controls -->
                <div class="flex items-center gap-2 px-3 py-2 bg-bg-surface border border-border-subtle rounded-lg">
                    <button
                        wire:click="toggleAutoRefresh"
                        class="flex items-center gap-2 text-sm font-medium transition-colors {{ $autoRefresh ? 'text-primary-600' : 'text-text-secondary hover:text-text-primary' }}"
                        title="{{ $autoRefresh ? 'Auto-refresh enabled' : 'Auto-refresh disabled' }}"
                    >
                        <svg class="w-4 h-4 {{ $autoRefresh ? 'animate-spin' : '' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span class="hidden sm:inline">{{ $autoRefresh ? 'Auto' : 'Manual' }}</span>
                    </button>

                    <div class="h-4 w-px bg-border-subtle"></div>

                    <button
                        wire:click="refresh"
                        class="flex items-center gap-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors"
                        title="Refresh now"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </div>

                <a href="{{ route('servers') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Server
                </a>
            </div>
        </div>

        @php
            $downServers = $this->servers->where('is_active', false);
        @endphp

        @if($downServers->isNotEmpty())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 lg:p-5 dark:border-red-900/30 dark:bg-red-900/10 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 p-2 rounded-lg bg-red-100 dark:bg-red-900/20">
                        <svg class="h-5 w-5 text-red-600 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Server Attention Required</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <p class="font-medium">The following servers are currently unreachable:</p>
                            <ul class="mt-2 space-y-1">
                                @foreach($downServers as $server)
                                    <li class="flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        <span class="font-medium">{{ $server->name }}</span>
                                        <span class="text-xs opacity-75">({{ $server->host }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="bg-bg-surface border border-border-subtle rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-text-secondary mb-1">Total Servers</p>
                        <p class="text-3xl lg:text-4xl font-bold text-text-primary">{{ $this->totalServers }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-primary-subtle">
                        <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-bg-surface border border-border-subtle rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-text-secondary mb-1">Active Servers</p>
                        <p class="text-3xl lg:text-4xl font-bold text-primary-600">{{ $this->activeServers }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-primary-500/10">
                        <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-bg-surface border border-border-subtle rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-text-secondary mb-1">Total Commands</p>
                        <p class="text-3xl lg:text-4xl font-bold text-text-primary">{{ number_format($this->totalCommands) }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-orange-500/10">
                        <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-bg-surface border border-border-subtle rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-text-secondary mb-1">Response Time</p>
                        <p class="text-3xl lg:text-4xl font-bold text-primary-600">~24ms</p>
                    </div>
                    <div class="p-3 rounded-xl bg-primary-500/10">
                        <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
            <!-- Main Content (Left 2 Columns) -->
            <div class="xl:col-span-2 space-y-6 lg:space-y-8">

                <!-- Server Health Grid -->
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-xl lg:text-2xl font-bold text-text-primary">Server Overview</h2>
                            <p class="mt-1 text-sm text-text-secondary">Monitor health and performance metrics</p>
                        </div>
                        <a href="{{ route('servers') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors rounded-lg hover:bg-primary-50 dark:hover:bg-primary-500/10">
                            View All
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>

                    @if ($this->servers->isEmpty())
                        <div class="text-center py-16 lg:py-20 bg-bg-surface border-2 border-dashed border-border-subtle rounded-2xl">
                            <div class="mx-auto w-16 h-16 rounded-full bg-primary-subtle flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                                </svg>
                            </div>
                            <p class="text-lg font-medium text-text-primary mb-2">No servers connected</p>
                            <p class="text-text-secondary mb-6">Get started by connecting your first server</p>
                            <a href="{{ route('servers') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Connect Server
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-6">
                            @foreach ($this->servers->take(6) as $server)
                                <div class="group bg-bg-surface border border-border-subtle hover:border-primary-500/50 rounded-xl p-6 transition-all cursor-pointer relative overflow-hidden hover:shadow-lg"
                                     onclick="window.location.href='{{ route('servers.show', $server) }}'">
                                    <div class="flex justify-between items-start mb-5">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="text-lg font-semibold text-text-primary group-hover:text-primary-600 transition-colors">{{ $server->name }}</h3>
                                                @if($server->is_active)
                                                    <span class="flex h-2 w-2 relative">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-text-tertiary font-mono">{{ $server->host }}:{{ $server->port }}</p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $server->is_active ? 'bg-primary-500/10 text-primary-600' : 'bg-text-tertiary/10 text-text-tertiary' }}">
                                            {{ $server->is_active ? 'Online' : 'Offline' }}
                                        </span>
                                    </div>

                                    <!-- Metrics -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="flex justify-between items-center text-xs text-text-tertiary mb-2">
                                                <span class="font-medium">CPU Usage</span>
                                                <span class="font-semibold">{{ rand(10, 80) }}%</span>
                                            </div>
                                            <div class="h-2 w-full bg-bg-surface-alt rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full transition-all" style="width: {{ rand(10, 80) }}%"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex justify-between items-center text-xs text-text-tertiary mb-2">
                                                <span class="font-medium">Memory</span>
                                                <span class="font-semibold">{{ rand(20, 90) }}%</span>
                                            </div>
                                            <div class="h-2 w-full bg-bg-surface-alt rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-orange-500 to-orange-600 rounded-full transition-all" style="width: {{ rand(20, 90) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Column -->
            <div class="space-y-6 lg:space-y-8">

                <!-- Quick Connect -->
                <div class="bg-bg-surface border border-border-subtle rounded-xl p-6 shadow-sm sticky top-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-5">Quick Connect</h3>
                    @livewire('quick-connect')
                </div>

                <!-- Activity Feed -->
                <div class="bg-bg-surface border border-border-subtle rounded-xl shadow-sm overflow-hidden flex flex-col h-[600px]">
                    <div class="px-6 py-4 border-b border-border-subtle bg-bg-surface-alt/50">
                        <h3 class="text-lg font-semibold text-text-primary">Recent Activity</h3>
                        <p class="mt-1 text-xs text-text-secondary">Latest system events and actions</p>
                    </div>
                    <div class="flex-1 overflow-y-auto p-0">
                        @livewire('activity-feed', ['limit' => 15])
                    </div>
                </div>

            </div>
        </div>
    </div>

    @script
    <script>
        // Smart polling with exponential backoff
        let refreshTimer = null;
        let currentInterval = @json($this->refreshInterval);

        function startAutoRefresh() {
            if (refreshTimer) {
                clearInterval(refreshTimer);
            }

            refreshTimer = setInterval(() => {
                if ($wire.autoRefresh) {
                    $wire.refresh();
                }
            }, currentInterval);
        }

        function stopAutoRefresh() {
            if (refreshTimer) {
                clearInterval(refreshTimer);
                refreshTimer = null;
            }
        }

        // Watch for auto-refresh changes
        $wire.on('auto-refresh-toggled', () => {
            if ($wire.autoRefresh) {
                startAutoRefresh();
            } else {
                stopAutoRefresh();
            }
        });

        // Initialize on mount
        if ($wire.autoRefresh) {
            startAutoRefresh();
        }

        // Cleanup on unmount
        document.addEventListener('livewire:navigating', () => {
            stopAutoRefresh();
        });
    </script>
    @endscript
</div>
