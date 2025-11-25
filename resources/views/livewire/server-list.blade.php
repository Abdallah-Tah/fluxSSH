<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">SSH Servers</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Manage and connect to your remote servers</p>
        </div>
        <button wire:click="addServer"
            class="group inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/25 transition-all duration-300 hover:scale-105 hover:-translate-y-0.5">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Server
        </button>
    </div>

    <!-- Search Bar -->
    <div class="relative max-w-2xl mx-auto">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </div>
        <input wire:model.live.debounce.300ms="search" type="search"
            placeholder="Search servers by name, host, or username..."
            class="w-full pl-12 pr-4 py-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 shadow-sm" />
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div
            class="flex items-center gap-3 p-4 bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/50 dark:border-emerald-500/20 rounded-xl backdrop-blur-sm animate-fade-in">
            <div
                class="shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="flex items-center gap-3 p-4 bg-red-50/80 dark:bg-red-500/10 border border-red-200/50 dark:border-red-500/20 rounded-xl backdrop-blur-sm animate-fade-in">
            <div
                class="shrink-0 w-10 h-10 bg-red-100 dark:bg-red-500/20 rounded-full flex items-center justify-center text-red-600 dark:text-red-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Server Cards Grid -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($servers as $server)
            <div
                class="group relative glass-card rounded-2xl overflow-hidden hover:border-emerald-500/30 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-1">
                <!-- Gradient accent line -->
                <div
                    class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $server->is_active ? 'from-emerald-500 to-teal-500' : 'from-zinc-300 to-zinc-400 dark:from-zinc-700 dark:to-zinc-600' }}">
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <!-- Server Icon -->
                        <div class="relative">
                            <div
                                class="w-14 h-14 rounded-2xl {{ $server->is_active ? 'bg-emerald-100 dark:bg-emerald-500/20' : 'bg-zinc-100 dark:bg-zinc-800' }} flex items-center justify-center transition-colors duration-300 group-hover:scale-105">
                                <svg class="w-7 h-7 {{ $server->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400' }}"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                                </svg>
                            </div>
                            @if($server->is_active)
                                <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-white dark:border-zinc-900"></span>
                                </span>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $server->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 ring-1 ring-emerald-500/20' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 ring-1 ring-zinc-500/20' }}">
                            {{ $server->is_active ? 'Online' : 'Offline' }}
                        </span>
                    </div>

                    <!-- Server Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-1 truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                            {{ $server->name }}
                        </h3>
                        <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 font-mono bg-zinc-50 dark:bg-zinc-800/50 px-2 py-1 rounded-md w-fit">
                            <span class="text-zinc-400 select-none">@</span>
                            {{ $server->username }}
                            <span class="text-zinc-300 dark:text-zinc-600 select-none">|</span>
                            {{ $server->host }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800/50">
                        <button wire:click="connectToServer({{ $server->id }})"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-zinc-900 dark:bg-white hover:bg-zinc-800 dark:hover:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z" />
                            </svg>
                            Connect
                        </button>

                        <div class="flex items-center gap-1">
                            <button wire:click="editServer({{ $server->id }})"
                                class="p-2 rounded-xl text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                                title="Edit Server">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>

                            <button wire:click="deleteServer({{ $server->id }})"
                                wire:confirm="Are you sure you want to delete this server?"
                                class="p-2 rounded-xl text-zinc-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                title="Delete Server">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div
                    class="text-center py-20 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm rounded-3xl border-2 border-dashed border-zinc-200 dark:border-zinc-800">
                    <div
                        class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-center ring-1 ring-zinc-100 dark:ring-zinc-800">
                        <svg class="w-12 h-12 text-zinc-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">
                        {{ $search ? 'No servers found' : 'No servers yet' }}
                    </h3>
                    <p class="text-zinc-500 dark:text-zinc-400 mb-8 max-w-sm mx-auto px-4">
                        {{ $search ? 'Try adjusting your search query or add a new server.' : 'Get started by adding your first SSH server connection.' }}
                    </p>
                    @if (!$search)
                        <button wire:click="addServer"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add Your First Server
                        </button>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($servers->hasPages())
        <div class="mt-8">
            {{ $servers->links() }}
        </div>
    @endif

    <!-- Server Form Modal -->
    @if ($showForm)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div wire:click="$set('showForm', false)"
                    class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity"></div>

                <!-- Modal Panel -->
                <div
                    class="relative inline-block transform overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle border border-zinc-200 dark:border-zinc-800">
                    <!-- Header -->
                    <div class="border-b border-zinc-200 dark:border-zinc-800 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/20">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white">
                                {{ $editingServer ? 'Edit Server' : 'Add New Server' }}
                            </h3>
                            <button wire:click="$set('showForm', false)"
                                class="p-2 rounded-lg text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6">
                        <livewire:server-form :server="$editingServer" wire:key="{{ $editingServer?->id ?? 'new' }}" />
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
