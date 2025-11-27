@php
    $tabs = [
        'overview' => 'Overview',
        'logs' => 'Logs',
        'activity' => 'Activity',
        'settings' => 'Settings',
    ];
@endphp

<div class="flex h-full flex-col bg-zinc-50 dark:bg-zinc-950" @if ($activeTab === 'overview') wire:poll.5s="refreshStats" @endif>
    <!-- Header -->
    <header class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
        <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Server Info -->
                <div class="flex items-center gap-4">
                    <!-- Server Icon -->
                    <div class="relative">
                        <div
                            class="w-14 h-14 rounded-2xl {{ $server->is_active ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-zinc-100 dark:bg-zinc-800' }} flex items-center justify-center">
                            <svg class="w-7 h-7 {{ $server->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-500 dark:text-zinc-400' }}"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                            </svg>
                        </div>
                        @if ($server->is_active)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white dark:border-zinc-900"></span>
                            </span>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white">{{ $server->name }}
                            </h1>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $server->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ $server->is_active ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                {{ $server->is_active ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3 mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            <code
                                class="font-mono text-zinc-700 dark:text-zinc-300">{{ $server->host }}:{{ $server->port }}</code>
                            <span class="w-1 h-1 rounded-full bg-zinc-300 dark:bg-zinc-600"></span>
                            <span>{{ $server->region ?? 'Default Region' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('console', $server) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-sm font-semibold rounded-xl hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span class="hidden sm:inline">Open</span> Console
                    </a>
                    <button type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-emerald-500/25">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        Deploy
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="px-4 sm:px-6 lg:px-8">
            <nav class="flex gap-1 overflow-x-auto scrollbar-hide -mb-px">
                @foreach ($tabs as $key => $label)
                    <button
                        type="button"
                        wire:click="setTab('{{ $key }}')"
                        @class([
                            'px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors',
                            'border-emerald-500 text-emerald-600 dark:text-emerald-400' => $activeTab === $key,
                            'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300 hover:border-zinc-300 dark:hover:border-zinc-600' => $activeTab !== $key,
                        ])>
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto">
            @if ($activeTab === 'overview')
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Column -->
                <div class="lg:col-span-2 space-y-6">
                    @php
                        $details = json_decode($server->server_details ?? '{}', true);
                        $memUsed = $details['memory_used_mb'] ?? 0;
                        $memTotal = $details['memory_total_mb'] ?? 8192;
                        $diskUsed = $details['disk_used'] ?? '0G';
                        $diskTotal = $details['disk_total'] ?? '100G';
                    @endphp

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <!-- CPU Card -->
                        <div
                            class="relative overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full -mr-8 -mt-8"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">CPU Usage</span>
                                    </div>
                                    <div wire:loading wire:target="refreshStats">
                                        <svg class="w-4 h-4 animate-spin text-zinc-400" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($server->cpu_usage ?? 0, 1) }}%</span>
                                </div>
                                <div class="mt-3 h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full transition-all duration-500"
                                        style="width: {{ min($server->cpu_usage ?? 0, 100) }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Memory Card -->
                        <div
                            class="relative overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/5 rounded-full -mr-8 -mt-8">
                            </div>
                            <div class="relative">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Memory</span>
                                    </div>
                                    <div wire:loading wire:target="refreshStats">
                                        <svg class="w-4 h-4 animate-spin text-zinc-400" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($memUsed / 1024, 1) }}</span>
                                    <span class="text-lg text-zinc-500 dark:text-zinc-400">/ {{ number_format($memTotal / 1024, 1) }} GB</span>
                                </div>
                                <div class="mt-3 h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-purple-500 rounded-full transition-all duration-500"
                                        style="width: {{ min($server->memory_usage ?? 0, 100) }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Disk Card -->
                        <div
                            class="relative overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-full -mr-8 -mt-8">
                            </div>
                            <div class="relative">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Disk</span>
                                    </div>
                                    <div wire:loading wire:target="refreshStats">
                                        <svg class="w-4 h-4 animate-spin text-zinc-400" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $diskUsed }}</span>
                                    <span class="text-lg text-zinc-500 dark:text-zinc-400">/ {{ $diskTotal }}</span>
                                </div>
                                <div class="mt-3 h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full transition-all duration-500"
                                        style="width: {{ min($server->disk_usage ?? 0, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                        @if ($hasError)
                            <div class="rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800 dark:text-red-200">Failed to fetch server statistics</p>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-0.5">{{ $errorMessage }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Deployments -->
                    <div
                        class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                        <div
                            class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Recent Activity</h3>
                            <button
                                class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium">View
                                All</button>
                        </div>
                        <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            <li class="px-5 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white">Update
                                            configuration</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Deployed by Alex • 2
                                            minutes ago</p>
                                    </div>
                                    <code
                                        class="text-xs font-mono text-zinc-400 dark:text-zinc-500 bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded">#8f3a21</code>
                                </div>
                            </li>
                            <li class="px-5 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white">Security patch
                                            applied</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Deployed by System • 1 hour
                                            ago</p>
                                    </div>
                                    <code
                                        class="text-xs font-mono text-zinc-400 dark:text-zinc-500 bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded">#1b9d4e</code>
                                </div>
                            </li>
                            <li class="px-5 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white">Server restarted
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Triggered manually • 3
                                            hours ago</p>
                                    </div>
                                    <code
                                        class="text-xs font-mono text-zinc-400 dark:text-zinc-500 bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded">#f29a12</code>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="space-y-6">
                    <!-- Server Info Card -->
                    <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            Server Information
                        </h3>
                        <dl class="space-y-4">
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-sm text-zinc-500 dark:text-zinc-400">Operating System</dt>
                                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $server->os_info ?? 'Unknown' }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-sm text-zinc-500 dark:text-zinc-400">Kernel</dt>
                                <dd class="text-sm font-mono text-zinc-900 dark:text-white">{{ $server->kernel_version ?? 'Unknown' }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-sm text-zinc-500 dark:text-zinc-400">Uptime</dt>
                                <dd class="text-sm font-medium text-emerald-600 dark:text-emerald-400">{{ $server->uptime ?? 'Unknown' }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-sm text-zinc-500 dark:text-zinc-400">Last Updated</dt>
                                <dd class="text-sm font-medium text-zinc-900 dark:text-white">
                                    {{ $server->last_detail_fetch_at ? $server->last_detail_fetch_at->diffForHumans() : 'Never' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Quick Actions
                        </h3>
                        <div class="space-y-2">
                            <button
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Restart Server
                            </button>
                            <button
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                </svg>
                                Create Backup
                            </button>
                            <button
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Server Settings
                            </button>
                            <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800 mt-2">
                                <button
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
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
            </div>
            @endif

            @if ($activeTab === 'logs')
                <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Logs</p>
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Recent Command History</h3>
                        </div>
                        <a href="{{ route('console', $server) }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                            </svg>
                            Open Console
                        </a>
                    </div>
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($this->commandLogs as $log)
                            <div class="px-5 py-4 flex items-start gap-4">
                                <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12l3 3 9-9" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 space-y-2">
                                    <div class="flex items-center justify-between gap-3 flex-wrap">
                                        <p class="font-medium text-zinc-900 dark:text-white font-mono text-sm break-all">{{ $log->command }}</p>
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-zinc-500 dark:text-zinc-400">
                                        @if ($log->current_directory)
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A1.5 1.5 0 014.5 6h3.879a1.5 1.5 0 011.06.44l1.121 1.12a1.5 1.5 0 001.06.44H19.5A1.5 1.5 0 0121 9.5v7a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 16.5v-9z" />
                                                </svg>
                                                {{ $log->current_directory }}
                                            </span>
                                        @endif
                                        @if ($log->execution_time)
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                                                </svg>
                                                {{ number_format((float) $log->execution_time, 3) }}s
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                <p>No command logs yet.</p>
                                <p class="mt-1 text-xs">Execute commands in the console to see them here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($activeTab === 'activity')
                <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Activity</p>
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Latest Events</h3>
                        </div>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Most recent 15 items</span>
                    </div>
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($this->activityLogEntries as $activity)
                            @php
                                $badgeClasses = match ($activity->type) {
                                    'connection' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300',
                                    'command' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300',
                                    'error' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                    default => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200',
                                };
                            @endphp
                            <div class="px-5 py-4 flex items-start gap-4">
                                <div class="p-2 rounded-lg {{ $badgeClasses }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 space-y-2">
                                    <div class="flex items-start justify-between gap-3 flex-wrap">
                                        <div>
                                            <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $activity->description }}</p>
                                            <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                                <span class="capitalize">{{ $activity->type }}</span>
                                                <span class="w-1 h-1 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
                                                <span class="capitalize">{{ $activity->action }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div class="flex flex-wrap gap-3 text-xs text-zinc-500 dark:text-zinc-400">
                                        @if ($activity->ip_address)
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 12c2.21 0 4-1.343 4-3s-1.79-3-4-3-4 1.343-4 3 1.79 3 4 3z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 12c0 5 4 8 9 8s9-3 9-8" />
                                                </svg>
                                                {{ $activity->ip_address }}
                                            </span>
                                        @endif
                                        @if ($activity->server)
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                                                </svg>
                                                {{ $activity->server->name }}
                                            </span>
                                        @endif
                                    </div>

                                    @if ($activity->metadata)
                                        <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800 rounded-lg p-3 text-xs text-zinc-600 dark:text-zinc-300 font-mono">
                                            @foreach ($activity->metadata as $key => $value)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-zinc-500">{{ $key }}:</span>
                                                    <span>{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                <p>No activity recorded yet.</p>
                                <p class="mt-1 text-xs">Connections, commands, and errors will show up here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($activeTab === 'settings')
                <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Settings</p>
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Update Connection Details</h3>
                        </div>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Changes save immediately</span>
                    </div>
                    <div class="p-5">
                        <livewire:server-form :server="$server" :reset-after-save="false" wire:key="server-settings-{{ $server->id }}" />
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
