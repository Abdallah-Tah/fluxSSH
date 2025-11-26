<x-layouts.app title="Dashboard">
    <div class="min-h-full pb-20 lg:pb-0">
        <!-- Header -->
        <header class="bg-black/50 backdrop-blur-xl border-b border-white/5 sticky top-0 z-30">
            <div class="px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Dashboard</h1>
                        <p class="mt-1 text-sm text-zinc-400">Overview of your infrastructure.</p>
                    </div>
                    <a href="{{ route('servers') }}"
                        class="group inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        New Connection
                    </a>
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-6 lg:p-8 space-y-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Total Servers -->
                <div class="bg-[#1c1c1e] rounded-2xl p-5 relative overflow-hidden group hover:bg-[#2c2c2e] transition-all duration-300 border border-white/5">
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl text-blue-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-400">Total Servers</span>
                        </div>
                        <p class="text-3xl font-bold text-white tracking-tight">
                            {{ \App\Models\Server::count() }}
                        </p>
                    </div>
                </div>

                <!-- Online Servers -->
                <div class="bg-[#1c1c1e] rounded-2xl p-5 relative overflow-hidden group hover:bg-[#2c2c2e] transition-all duration-300 border border-white/5">
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2.5 bg-green-500/10 rounded-xl text-green-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-400">Online</span>
                        </div>
                        <p class="text-3xl font-bold text-white tracking-tight">
                            {{ \App\Models\Server::where('is_active', true)->count() }}
                        </p>
                    </div>
                </div>

                <!-- Offline Servers -->
                <div class="bg-[#1c1c1e] rounded-2xl p-5 relative overflow-hidden group hover:bg-[#2c2c2e] transition-all duration-300 border border-white/5">
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2.5 bg-red-500/10 rounded-xl text-red-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-400">Offline</span>
                        </div>
                        <p class="text-3xl font-bold text-white tracking-tight">
                            {{ \App\Models\Server::where('is_active', false)->count() }}
                        </p>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-[#1c1c1e] rounded-2xl p-5 relative overflow-hidden group hover:bg-[#2c2c2e] transition-all duration-300 border border-white/5">
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2.5 bg-purple-500/10 rounded-xl text-purple-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-400">Recent Connections</span>
                        </div>
                        <p class="text-3xl font-bold text-white tracking-tight">
                            {{ \App\Models\Server::whereNotNull('last_connected_at')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Servers List -->
            <div class="bg-[#1c1c1e] rounded-2xl overflow-hidden border border-white/5">
                <div class="px-6 py-4 border-b border-white/5">
                    <h3 class="text-lg font-bold text-white">Recent Servers</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/5">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Server</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider hidden sm:table-cell">IP Address</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-zinc-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse (\App\Models\Server::latest()->take(5)->get() as $server)
                                <tr class="group hover:bg-white/5 transition-colors cursor-pointer"
                                    onclick="window.location='{{ route('servers.show', $server) }}'">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-[#2c2c2e] flex items-center justify-center">
                                                <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-white group-hover:text-blue-400 transition-colors">
                                                    {{ $server->name }}
                                                </p>
                                                <p class="text-xs text-zinc-500 font-mono mt-0.5 sm:hidden">
                                                    {{ $server->host }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden sm:table-cell">
                                        <code class="px-2 py-1 rounded bg-[#2c2c2e] text-xs font-mono text-zinc-400 border border-white/5">
                                            {{ $server->host }}
                                        </code>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $server->is_active ? 'bg-green-500/10 text-green-400' : 'bg-zinc-500/10 text-zinc-400' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $server->is_active ? 'bg-green-500' : 'bg-zinc-500' }}"></span>
                                            {{ $server->is_active ? 'Online' : 'Offline' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('console', $server) }}" onclick="event.stopPropagation();"
                                            class="inline-flex items-center justify-center p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/10 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-zinc-500">
                                        No recent servers found.
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
