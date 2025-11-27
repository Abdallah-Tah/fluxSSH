<div class="h-full p-6 lg:p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-semibold text-text-primary tracking-tight">Dashboard</h1>
        <p class="text-sm text-text-secondary">Overview of your infrastructure and recent activity.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Left 2 Columns) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-bg-surface border border-border-subtle rounded-xl p-5 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-lg bg-primary-subtle text-primary-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-text-secondary">Total Servers</span>
                    </div>
                    <div class="text-2xl font-bold text-text-primary">{{ $this->totalServers }}</div>
                </div>

                <div class="bg-bg-surface border border-border-subtle rounded-xl p-5 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-lg bg-primary-500/10 text-primary-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-text-secondary">Active</span>
                    </div>
                    <div class="text-2xl font-bold text-text-primary">{{ $this->activeServers }}</div>
                </div>

                <div class="bg-bg-surface border border-border-subtle rounded-xl p-5 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-lg bg-orange-500/10 text-orange-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-text-secondary">Commands</span>
                    </div>
                    <div class="text-2xl font-bold text-text-primary">{{ number_format($this->totalCommands) }}</div>
                </div>
            </div>

            <!-- Server Health Grid -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-text-primary">Server Health</h2>
                    <a href="{{ route('servers') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors">View All</a>
                </div>
                
                @if ($this->servers->isEmpty())
                    <div class="text-center py-12 bg-bg-surface border border-border-subtle rounded-xl border-dashed">
                        <p class="text-text-secondary mb-4">No servers connected.</p>
                        <a href="{{ route('servers') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                            Connect Server
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($this->servers->take(4) as $server)
                            <div class="group bg-bg-surface border border-border-subtle rounded-xl p-5 hover:border-primary-500/50 transition-colors cursor-pointer relative overflow-hidden"
                                 onclick="window.location.href='{{ route('servers.show', $server) }}'">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-medium text-text-primary group-hover:text-primary-600 transition-colors">{{ $server->name }}</h3>
                                        <p class="text-xs text-text-tertiary font-mono mt-0.5">{{ $server->host }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-2 w-2 rounded-full {{ $server->is_active ? 'bg-primary-500' : 'bg-text-tertiary' }}"></span>
                                    </div>
                                </div>
                                
                                <!-- Fake Sparklines for Visual -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="flex justify-between text-[10px] text-text-tertiary mb-1">
                                            <span>CPU</span>
                                            <span>{{ rand(10, 80) }}%</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-bg-surface-alt rounded-full overflow-hidden">
                                            <div class="h-full bg-primary-500/20 rounded-full" style="width: {{ rand(10, 80) }}%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-[10px] text-text-tertiary mb-1">
                                            <span>RAM</span>
                                            <span>{{ rand(20, 90) }}%</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-bg-surface-alt rounded-full overflow-hidden">
                                            <div class="h-full bg-orange-500/20 rounded-full" style="width: {{ rand(20, 90) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Column (Activity & Quick Actions) -->
        <div class="space-y-8">
            
            <!-- Quick Connect -->
            <div class="bg-bg-surface border border-border-subtle rounded-xl p-5 shadow-sm">
                <h3 class="text-sm font-medium text-text-primary mb-4">Quick Connect</h3>
                @livewire('quick-connect')
            </div>

            <!-- Activity Feed -->
            <div class="bg-bg-surface border border-border-subtle rounded-xl shadow-sm overflow-hidden flex flex-col h-[500px]">
                <div class="px-5 py-4 border-b border-border-subtle bg-bg-surface-alt/50">
                    <h3 class="text-sm font-medium text-text-primary">Activity Feed</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-0">
                    @livewire('activity-feed', ['limit' => 10])
                </div>
            </div>

        </div>
    </div>
</div>
