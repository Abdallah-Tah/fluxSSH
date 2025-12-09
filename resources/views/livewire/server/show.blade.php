@php
    $tabs = [
        'overview' => 'Overview',
        'logs' => 'Logs',
        'activity' => 'Activity',
        'settings' => 'Settings',
    ];
@endphp

<div class="flex h-full flex-col bg-bg-app" @if ($activeTab === 'overview') wire:poll.5s="refreshStats" @endif>
    <!-- Header -->
    <header class="bg-bg-surface border-b border-border-subtle">
        <div class="px-6 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Server Info -->
                <div class="flex items-center gap-4">
                    <div
                        class="relative w-12 h-12 rounded-lg bg-bg-surface-alt border border-border-subtle flex items-center justify-center">
                        <svg class="w-6 h-6 text-text-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                        </svg>
                        @if ($server->is_active)
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                            </span>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-xl font-bold text-text-primary">{{ $server->name }}</h1>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $server->is_active ? 'bg-primary-500/10 text-primary-600' : 'bg-bg-surface-alt text-text-tertiary' }}">
                                {{ $server->is_active ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3 mt-1 text-sm text-text-tertiary font-mono">
                            <span>{{ $server->host }}:{{ $server->port }}</span>
                            <span class="text-border-subtle">|</span>
                            <span>{{ $server->region ?? 'Default Region' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('terminal.ttyd', $server) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                        </svg>
                        Professional Terminal
                    </a>
                    <a href="{{ route('shell', $server) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-bg-surface-alt hover:bg-bg-surface-alt/80 text-text-primary text-sm font-medium rounded-md shadow-sm transition-colors border border-border-subtle">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Standard Terminal
                    </a>
                    <a href="{{ route('console', $server) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-bg-surface-alt hover:bg-bg-surface-alt/80 text-text-primary text-sm font-medium rounded-md shadow-sm transition-colors border border-border-subtle">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Legacy Console
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="px-6">
            <nav class="flex gap-6 -mb-px">
                @foreach ($tabs as $key => $label)
                    <button type="button" wire:click="setTab('{{ $key }}')" @class([
                        'pb-3 text-sm font-medium border-b-2 transition-colors',
                        'border-primary-500 text-primary-600' => $activeTab === $key,
                        'border-transparent text-text-secondary hover:text-text-primary hover:border-border-strong' =>
                            $activeTab !== $key,
                    ])>
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto p-6 lg:p-8">
        <div class="max-w-7xl mx-auto">
            @if ($activeTab === 'overview')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Stats -->
                    <div class="lg:col-span-2 space-y-6">
                        @php
                            $details = json_decode($server->server_details ?? '{}', true);
                            $memUsed = $details['memory_used_mb'] ?? 0;
                            $memTotal = $details['memory_total_mb'] ?? 8192;
                            $diskUsed = $details['disk_used'] ?? '0G';
                            $diskTotal = $details['disk_total'] ?? '100G';
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- CPU -->
                            <div class="bg-bg-surface border border-border-subtle rounded-xl p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-medium text-text-secondary">CPU Usage</span>
                                    <svg class="w-4 h-4 text-text-tertiary" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="text-3xl font-bold text-text-primary mb-2">
                                    {{ number_format($server->cpu_usage ?? 0, 1) }}%</div>
                                <div class="h-1.5 w-full bg-bg-surface-alt rounded-full overflow-hidden">
                                    <div class="h-full bg-primary-500 rounded-full transition-all duration-500"
                                        style="width: {{ min($server->cpu_usage ?? 0, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Memory -->
                            <div class="bg-bg-surface border border-border-subtle rounded-xl p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-medium text-text-secondary">Memory</span>
                                    <svg class="w-4 h-4 text-text-tertiary" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                </div>
                                <div class="text-3xl font-bold text-text-primary mb-2">
                                    {{ number_format($memUsed / 1024, 1) }} <span
                                        class="text-sm font-normal text-text-tertiary">GB</span></div>
                                <div class="h-1.5 w-full bg-bg-surface-alt rounded-full overflow-hidden">
                                    <div class="h-full bg-orange-500 rounded-full transition-all duration-500"
                                        style="width: {{ min($server->memory_usage ?? 0, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Disk -->
                            <div class="bg-bg-surface border border-border-subtle rounded-xl p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-medium text-text-secondary">Disk</span>
                                    <svg class="w-4 h-4 text-text-tertiary" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                    </svg>
                                </div>
                                <div class="text-3xl font-bold text-text-primary mb-2">{{ $diskUsed }}</div>
                                <div class="h-1.5 w-full bg-bg-surface-alt rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full transition-all duration-500"
                                        style="width: {{ min($server->disk_usage ?? 0, 100) }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Terminal Preview -->
                        <div class="bg-bg-code rounded-xl border border-border-subtle overflow-hidden">
                            <div
                                class="px-4 py-2 border-b border-white/10 bg-white/5 flex items-center justify-between">
                                <span class="text-xs font-mono text-text-tertiary">Terminal Preview</span>
                                <div class="flex gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded-full bg-red-500/50"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/50"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-green-500/50"></div>
                                </div>
                            </div>
                            <div class="p-4 font-mono text-sm text-text-secondary space-y-1">
                                <div class="flex gap-2">
                                    <span class="text-primary-500">root@{{ $server - > name }}:~#</span>
                                    <span>uptime</span>
                                </div>
                                <div class="text-text-tertiary">{{ $server->uptime ?? '0 min' }}</div>
                                <div class="flex gap-2">
                                    <span class="text-primary-500">root@{{ $server - > name }}:~#</span>
                                    <span class="animate-pulse">_</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="space-y-6">
                        <div class="bg-bg-surface border border-border-subtle rounded-xl p-5">
                            <h3 class="text-sm font-medium text-text-primary mb-4">System Details</h3>
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-text-tertiary">OS</dt>
                                    <dd class="text-text-primary font-medium">{{ $server->os_info ?? 'Unknown' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-text-tertiary">Kernel</dt>
                                    <dd class="text-text-primary font-medium">
                                        {{ $server->kernel_version ?? 'Unknown' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-text-tertiary">Last Updated</dt>
                                    <dd class="text-text-primary font-medium">
                                        {{ $server->last_detail_fetch_at ? $server->last_detail_fetch_at->diffForHumans() : 'Never' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-bg-surface border border-border-subtle rounded-xl p-5">
                            <h3 class="text-sm font-medium text-text-primary mb-4">Actions</h3>
                            <div class="space-y-2">
                                <button
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-text-secondary hover:bg-bg-surface-alt hover:text-text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Restart Server
                                </button>
                                <button
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-danger hover:bg-red-900/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" />
                                    </svg>
                                    Power Off
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($activeTab === 'logs')
                <div class="bg-bg-surface border border-border-subtle rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-border-subtle">
                        <h3 class="text-sm font-medium text-text-primary">Command History</h3>
                    </div>
                    <div class="divide-y divide-border-subtle">
                        @forelse ($this->commandLogs as $log)
                            <div class="px-5 py-3 flex items-start gap-3 hover:bg-bg-surface-alt transition-colors">
                                <div class="mt-1 text-text-tertiary font-mono text-xs">
                                    {{ $log->created_at->format('H:i:s') }}</div>
                                <div class="flex-1 font-mono text-sm text-text-primary">{{ $log->command }}</div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-sm text-text-tertiary">No logs available.</div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($activeTab === 'activity')
                <div class="bg-bg-surface border border-border-subtle rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-border-subtle">
                        <h3 class="text-sm font-medium text-text-primary">Activity Log</h3>
                    </div>
                    <div class="divide-y divide-border-subtle">
                        @forelse ($this->activityLogEntries as $activity)
                            <div class="px-5 py-4 flex items-start gap-4">
                                <div class="p-1.5 rounded bg-bg-surface-alt text-text-secondary">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-text-primary">{{ $activity->description }}</p>
                                    <p class="text-xs text-text-tertiary mt-0.5">
                                        {{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-sm text-text-tertiary">No activity recorded.</div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($activeTab === 'settings')
                <div class="bg-bg-surface border border-border-subtle rounded-xl p-6">
                    <h3 class="text-lg font-medium text-text-primary mb-6">Server Settings</h3>
                    <livewire:server-form :server="$server" :reset-after-save="false"
                        wire:key="server-settings-{{ $server->id }}" />
                </div>
            @endif
        </div>
    </main>
</div>
