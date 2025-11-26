<div class="min-h-full pb-32 lg:pb-0">
    <!-- Header -->
    <header
        class="flex items-center justify-between px-4 sm:px-8 py-6 bg-gradient-to-r from-emerald-500/5 via-teal-500/5 to-cyan-500/5 dark:from-emerald-500/10 dark:via-teal-500/10 dark:to-cyan-500/10 backdrop-blur-sm">
        <div class="flex items-center gap-2">
            <div
                class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">FluxSSH</h1>
        </div>
        <a href="{{ route('profile.edit') }}"
            class="p-2 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </a>
    </header>

    <main class="px-4 sm:px-8 space-y-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Total Servers -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-xl p-5 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-lg hover:shadow-cyan-500/10 transition-all group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg shadow-cyan-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Total Servers</p>
                        <p
                            class="text-2xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 dark:from-cyan-400 dark:to-blue-400 bg-clip-text text-transparent">
                            {{ $this->totalServers }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Servers -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-xl p-5 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-lg hover:shadow-emerald-500/10 transition-all group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Active Servers</p>
                        <p
                            class="text-2xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400 bg-clip-text text-transparent">
                            {{ $this->activeServers }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Commands -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-xl p-5 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-lg hover:shadow-purple-500/10 transition-all group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Commands Executed</p>
                        <p
                            class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
                            {{ number_format($this->totalCommands) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-zinc-400 dark:text-zinc-500 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search servers by name or host..."
                class="w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl py-3 pl-12 pr-4 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-600 focus:outline-none focus:border-cyan-500 dark:focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20 dark:focus:ring-cyan-500/20 shadow-sm transition-all" />
        </div>

        <!-- Server Grid -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Your Servers</h2>
                <a href="{{ route('servers') }}"
                    class="text-sm text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 transition-colors flex items-center gap-1 font-medium">
                    <span>Manage Servers</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            @if ($this->servers->isEmpty())
                <div
                    class="text-center py-12 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                    <div
                        class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">No servers found</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 mb-6">Get started by adding your first SSH server</p>
                    <a href="{{ route('servers') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white rounded-lg transition-all font-medium shadow-lg shadow-cyan-500/30 hover:shadow-xl hover:shadow-cyan-500/40 hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Server
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($this->servers as $server)
                        @php
                            $colors = [
                                'emerald' => [
                                    'bg' => 'bg-gradient-to-br from-emerald-500 to-teal-500',
                                    'border' => 'hover:border-emerald-500/50 dark:hover:border-emerald-500/30',
                                    'shadow' => 'hover:shadow-emerald-500/10',
                                    'text' => 'text-emerald-600 dark:text-emerald-400',
                                ],
                                'yellow' => [
                                    'bg' => 'bg-gradient-to-br from-yellow-400 to-orange-400',
                                    'border' => 'hover:border-yellow-500/50 dark:hover:border-yellow-500/30',
                                    'shadow' => 'hover:shadow-yellow-500/10',
                                    'text' => 'text-yellow-600 dark:text-yellow-400',
                                ],
                                'red' => [
                                    'bg' => 'bg-gradient-to-br from-red-500 to-rose-500',
                                    'border' => 'hover:border-red-500/50 dark:hover:border-red-500/30',
                                    'shadow' => 'hover:shadow-red-500/10',
                                    'text' => 'text-red-600 dark:text-red-400',
                                ],
                                'zinc' => [
                                    'bg' => 'bg-gradient-to-br from-zinc-400 to-zinc-500',
                                    'border' => 'hover:border-zinc-500/50 dark:hover:border-zinc-500/30',
                                    'shadow' => 'hover:shadow-zinc-500/10',
                                    'text' => 'text-zinc-600 dark:text-zinc-400',
                                ],
                            ];
                            $color = $colors[$server->status_color] ?? $colors['zinc'];
                        @endphp

                        <div wire:key="server-{{ $server->id }}"
                            class="group bg-white dark:bg-zinc-900 rounded-xl p-5 border border-zinc-200 dark:border-zinc-800 {{ $color['border'] }} {{ $color['shadow'] }} shadow-sm hover:shadow-lg transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg {{ $color['bg'] }} flex items-center justify-center text-white font-bold shadow-md group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4 6h16v12H4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-zinc-900 dark:text-white text-sm">
                                            {{ $server->name }}</h3>
                                        <p class="text-xs text-zinc-600 dark:text-zinc-400 font-mono">
                                            {{ $server->host }}:{{ $server->port }}</p>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium {{ $server->is_active ? 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' : 'bg-zinc-100 dark:bg-zinc-500/10 text-zinc-700 dark:text-zinc-400' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $server->is_active ? 'bg-emerald-600 dark:bg-emerald-400' : 'bg-zinc-500 dark:bg-zinc-400' }}"></span>
                                    {{ $server->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <!-- Server Stats -->
                            <div
                                class="grid grid-cols-3 gap-3 mb-4 pb-4 border-b border-zinc-200 dark:border-zinc-800">
                                <div>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Commands</p>
                                    <p class="text-sm font-bold text-zinc-900 dark:text-white">
                                        {{ $server->command_histories_count }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Last Active</p>
                                    <p class="text-sm font-bold text-zinc-900 dark:text-white">
                                        {{ $server->uptime_text }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Auth</p>
                                    <p class="text-sm font-bold text-zinc-900 dark:text-white">
                                        {{ ucfirst($server->auth_type) }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                <a href="{{ route('console', $server) }}"
                                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white text-sm font-semibold rounded-lg shadow-md shadow-cyan-500/20 hover:shadow-lg hover:shadow-cyan-500/30 transition-all hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                    Console
                                </a>
                                <a href="{{ route('servers.show', $server) }}"
                                    class="flex items-center justify-center px-3 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Activity -->
        @if ($this->recentActivity->isNotEmpty())
            <div
                class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between bg-gradient-to-r from-purple-500/5 via-pink-500/5 to-purple-500/5 dark:from-purple-500/10 dark:via-pink-500/10 dark:to-purple-500/10">
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Recent Activity</h3>
                    <span class="text-xs text-zinc-600 dark:text-zinc-400 font-medium">Last 5 commands</span>
                </div>
                <ul class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @foreach ($this->recentActivity as $activity)
                        <li class="px-5 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0 shadow-md shadow-purple-500/20">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-mono text-zinc-900 dark:text-white truncate font-medium">$
                                        {{ $activity->command }}</p>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">
                                        {{ $activity->server->name }} • {{ $activity->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if ($activity->execution_time)
                                    <code
                                        class="text-xs font-mono text-purple-700 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/10 px-2 py-1 rounded font-semibold">{{ number_format($activity->execution_time, 3) }}s</code>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </main>
</div>
