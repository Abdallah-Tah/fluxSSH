<x-layouts.app title="Dashboard">
    <div class="min-h-full">
        <!-- Header -->
        <header class="bg-white/50 dark:bg-zinc-900/50 backdrop-blur-xl border-b border-zinc-200/50 dark:border-zinc-800/50 sticky top-0 z-30">
            <div class="px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Dashboard</h1>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Welcome back! Here's an overview of your infrastructure.</p>
                    </div>
                    <a href="{{ route('servers') }}"
                        class="group inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-zinc-900 dark:bg-white hover:bg-zinc-800 dark:hover:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-semibold rounded-xl shadow-lg shadow-zinc-900/20 dark:shadow-white/20 transition-all duration-300 hover:scale-105 hover:-translate-y-0.5">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        New Server
                    </a>
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-6 lg:p-8 space-y-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Total Servers -->
                <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-emerald-500/30 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-10 -mt-10 blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2.5 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Servers</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">
                                {{ \App\Models\Server::count() }}
                            </p>
                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full">
                                +{{ \App\Models\Server::where('created_at', '>=', now()->subDays(7))->count() }} this week
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Online Servers -->
                <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-blue-500/30 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full -mr-10 -mt-10 blur-2xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2.5 bg-blue-50 dark:bg-blue-500/10 rounded-xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Online</span>
                        </div>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">
                            {{ \App\Models\Server::where('is_active', true)->count() }}
                        </p>
                    </div>
                </div>

                <!-- Offline Servers -->
                <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-amber-500/30 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full -mr-10 -mt-10 blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2.5 bg-amber-50 dark:bg-amber-500/10 rounded-xl text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Offline</span>
                        </div>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">
                            {{ \App\Models\Server::where('is_active', false)->count() }}
                        </p>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-purple-500/30 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full -mr-10 -mt-10 blur-2xl group-hover:bg-purple-500/20 transition-all duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2.5 bg-purple-50 dark:bg-purple-500/10 rounded-xl text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Recent Connections</span>
                        </div>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">
                            {{ \App\Models\Server::whereNotNull('last_connected_at')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="max-w-md">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-zinc-400 group-focus-within:text-emerald-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="search" placeholder="Search servers... (⌘K)"
                        class="w-full pl-12 pr-4 py-3 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 shadow-sm" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <kbd class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800 text-xs font-medium text-zinc-500 dark:text-zinc-400 font-mono">
                            <span class="text-xs">⌘</span>K
                        </kbd>
                    </div>
                </div>
            </div>

            <!-- Servers Table -->
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-zinc-50/50 dark:bg-zinc-800/20 border-b border-zinc-200/50 dark:border-zinc-800/50">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Server</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider hidden sm:table-cell">IP Address</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider hidden md:table-cell">Region</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider hidden lg:table-cell">CPU</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                            @php
                                $servers = \App\Models\Server::latest()->get();
                            @endphp

                            @forelse ($servers as $server)
                                <tr class="group hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors cursor-pointer"
                                    onclick="window.location='{{ route('servers.show', $server) }}'">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <div class="w-10 h-10 rounded-xl {{ $server->is_active ? 'bg-emerald-100 dark:bg-emerald-500/20' : 'bg-zinc-100 dark:bg-zinc-800' }} flex items-center justify-center transition-colors">
                                                    <svg class="w-5 h-5 {{ $server->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                                                    </svg>
                                                </div>
                                                @if($server->is_active)
                                                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                                    {{ $server->name }}
                                                </p>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-400 sm:hidden font-mono mt-0.5">
                                                    {{ $server->host }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden sm:table-cell">
                                        <div class="flex items-center gap-2">
                                            <code class="px-2 py-1 rounded-md bg-zinc-100 dark:bg-zinc-800 text-xs font-mono text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                                {{ $server->host }}:{{ $server->port }}
                                            </code>
                                            <button class="p-1 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors" onclick="event.stopPropagation(); navigator.clipboard.writeText('{{ $server->host }}')">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden md:table-cell">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                            </svg>
                                            {{ $server->region ?? 'Default' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $server->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 ring-1 ring-emerald-500/20' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 ring-1 ring-zinc-500/20' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $server->is_active ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                            {{ $server->is_active ? 'Online' : 'Offline' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-1 h-2 max-w-[80px] bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden ring-1 ring-zinc-200 dark:ring-zinc-700">
                                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $server->cpu_usage ?? rand(5, 45) }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 w-8">{{ $server->cpu_usage ?? rand(5, 45) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200" onclick="event.stopPropagation();">
                                            <a href="{{ route('console', $server) }}"
                                                class="p-2 rounded-lg text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors"
                                                title="Open Console">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                                                </svg>
                                            </a>
                                            <button class="p-2 rounded-lg text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-24 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-center mb-6 ring-1 ring-zinc-100 dark:ring-zinc-800">
                                                <svg class="w-10 h-10 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">No servers yet</h3>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-8 max-w-sm mx-auto">
                                                Get started by adding your first server to monitor and manage your infrastructure.
                                            </p>
                                            <a href="{{ route('servers') }}"
                                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:-translate-y-0.5">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                Add Server
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</x-layouts.app>
