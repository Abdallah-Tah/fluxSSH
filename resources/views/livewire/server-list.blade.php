<div class="space-y-6 pb-20 lg:pb-0">
    <!-- Header Section -->
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-text-primary tracking-tight">FluxSSH <span class="text-2xl">🔥</span></h1>
            <button wire:click="addServer" class="lg:hidden p-2 rounded-xl bg-bg-surface text-primary-600 hover:bg-bg-surface-alt transition-colors border border-border-subtle">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
        </div>
        
        <!-- Search Bar -->
        <div class="flex gap-3">
            <div class="relative flex-1 group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-text-tertiary group-focus-within:text-text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="search"
                    placeholder="Search"
                    class="w-full pl-10 pr-4 py-2.5 bg-bg-surface border border-border-subtle rounded-xl text-text-primary placeholder-text-tertiary focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" />
            </div>
            <button wire:click="addServer" class="hidden lg:flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-medium rounded-xl transition-colors shadow-sm shadow-primary-500/20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add New
            </button>
        </div>
    </div>

    <!-- Server Cards Grid -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($servers as $server)
            <div class="group relative bg-bg-surface rounded-2xl p-4 overflow-hidden hover:border-primary-500/50 transition-all duration-300 cursor-pointer border border-border-subtle shadow-sm hover:shadow-md"
                 wire:click="connectToServer({{ $server->id }})">
                
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <!-- Status Indicator -->
                        <div class="relative">
                            <div class="w-10 h-10 rounded-xl bg-bg-surface-alt flex items-center justify-center group-hover:bg-bg-surface-alt/80 transition-colors">
                                <div class="w-3 h-3 rounded-full {{ $server->is_active ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]' : 'bg-text-tertiary' }}"></div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-bold text-text-primary text-base">{{ $server->name }}</h3>
                            <p class="text-xs text-text-secondary font-mono">{{ $server->host }}</p>
                        </div>
                    </div>

                    <button wire:click.stop="editServer({{ $server->id }})" class="text-text-tertiary hover:text-text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full {{ $server->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                        <span class="text-xs font-medium {{ $server->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $server->is_active ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                    
                    @if($server->is_active)
                        <div class="flex items-center gap-1 text-xs text-text-tertiary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            <span>3 instances</span>
                        </div>
                    @endif
                </div>
                
                <!-- Loading State Overlay -->
                <div wire:loading.flex wire:target="connectToServer({{ $server->id }})" 
                     class="absolute inset-0 bg-bg-surface/80 backdrop-blur-sm items-center justify-center z-10">
                    <div class="flex items-center gap-2 text-text-primary font-medium">
                        <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Connecting...
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-bg-surface flex items-center justify-center border border-border-subtle">
                    <svg class="w-8 h-8 text-text-tertiary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-text-primary mb-2">No servers found</h3>
                <p class="text-text-secondary mb-6">Get started by adding your first server.</p>
                <button wire:click="addServer" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-colors shadow-sm shadow-primary-500/20">
                    Add Server
                </button>
            </div>
        @endforelse
    </div>

    <!-- Server Form Modal -->
    @if ($showForm)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center sm:items-center p-0 sm:p-4">
                <!-- Backdrop -->
                <div wire:click="$set('showForm', false)"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

                <!-- Modal Panel -->
                <div class="relative w-full sm:max-w-md transform overflow-hidden rounded-t-3xl sm:rounded-3xl bg-bg-surface text-left shadow-2xl transition-all border-t sm:border border-border-subtle">
                    <!-- Handle for mobile drag -->
                    <div class="sm:hidden w-full flex justify-center pt-3 pb-1">
                        <div class="w-12 h-1.5 rounded-full bg-border-subtle"></div>
                    </div>
                    
                    <div class="px-6 py-4 border-b border-border-subtle flex items-center justify-between">
                        <h3 class="text-lg font-bold text-text-primary">
                            {{ $editingServer ? 'Edit Connection' : 'New Connection' }}
                        </h3>
                        <button wire:click="$set('showForm', false)" class="text-text-tertiary hover:text-text-primary">
                            <span class="sr-only">Close</span>
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-6">
                        <livewire:server-form :server="$editingServer" wire:key="{{ $editingServer?->id ?? 'new' }}" />
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
