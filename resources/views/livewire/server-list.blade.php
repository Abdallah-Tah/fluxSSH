<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <flux:heading size="xl">SSH Servers</flux:heading>
        <flux:button wire:click="addServer" variant="primary">
            <flux:icon name="plus" class="mr-2" />
            Add Server
        </flux:button>
    </div>

    <!-- Search -->
    <div class="flex gap-4">
        <flux:field class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search servers..." type="search" />
        </flux:field>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <flux:callout variant="success">
            {{ session('message') }}
        </flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout variant="danger">
            {{ session('error') }}
        </flux:callout>
    @endif

    <!-- Server List -->
    <div class="grid gap-4">
        @forelse ($servers as $server)
            <div class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 transition-all hover:shadow-md hover:border-blue-200 dark:hover:border-blue-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                            <flux:heading size="lg" class="truncate">{{ $server->name }}</flux:heading>
                            <div class="flex items-center gap-2">
                                <flux:badge variant="{{ $server->is_active ? 'success' : 'muted' }}" size="sm" class="rounded-full">
                                    {{ $server->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                                <flux:badge variant="outline" size="sm" class="rounded-full text-xs">
                                    {{ $server->auth_type === 'key' ? 'Key' : 'Password' }}
                                </flux:badge>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <flux:icon name="server" class="w-4 h-4 flex-shrink-0" />
                                <span class="truncate font-mono">{{ $server->username }}@<span class="text-gray-900 dark:text-gray-300">{{ $server->ip_address }}</span>:{{ $server->port }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:self-center pt-4 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-gray-800">
                        <flux:button wire:click="testConnection({{ $server->id }})" variant="ghost" size="sm" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <flux:icon name="wifi" class="w-4 h-4 sm:mr-1" />
                            <span class="hidden sm:inline">Test</span>
                        </flux:button>

                        <flux:button wire:click="editServer({{ $server->id }})" variant="ghost" size="sm" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <flux:icon name="pencil" class="w-4 h-4 sm:mr-1" />
                            <span class="hidden sm:inline">Edit</span>
                        </flux:button>

                        <flux:separator vertical class="h-4 mx-1 hidden sm:block" />

                        <flux:button wire:click="deleteServer({{ $server->id }})"
                            wire:confirm="Are you sure you want to delete this server?" variant="ghost" size="sm" class="text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <flux:icon name="trash" class="w-4 h-4 sm:mr-1" />
                            <span class="hidden sm:inline">Delete</span>
                        </flux:button>

                         <flux:button wire:click="connectToServer({{ $server->id }})" variant="primary" size="sm" class="ml-2 shadow-sm">
                            <flux:icon name="power" class="w-4 h-4 mr-1.5" />
                            Connect
                        </flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                <div class="bg-gray-50 dark:bg-gray-800 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <flux:icon name="server" class="w-8 h-8 text-gray-400" />
                </div>
                <flux:heading size="lg" class="text-gray-900 dark:text-white mb-2">No servers found</flux:heading>
                <flux:text class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">
                    {{ $search ? 'We couldn\'t find any servers matching your search.' : 'Get started by adding your first SSH server connection.' }}
                </flux:text>
                @if (!$search)
                    <flux:button wire:click="addServer" variant="primary">
                        <flux:icon name="plus" class="mr-2" />
                        Add Server
                    </flux:button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($servers->hasPages())
        <div class="mt-6">
            {{ $servers->links() }}
        </div>
    @endif

    <!-- Server Form Modal -->
    @if ($showForm)
        <flux:modal wire:model="showForm" max-width="2xl">
            <div class="p-6">
                <flux:heading size="lg" class="mb-6">
                    {{ $editingServer ? 'Edit Server' : 'Add New Server' }}
                </flux:heading>

                <livewire:server-form :server="$editingServer" wire:key="{{ $editingServer?->id ?? 'new' }}" />
            </div>
        </flux:modal>
    @endif
</div>
