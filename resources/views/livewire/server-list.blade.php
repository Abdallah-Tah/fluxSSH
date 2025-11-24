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
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <flux:heading size="lg">{{ $server->name }}</flux:heading>
                            <flux:badge variant="{{ $server->is_active ? 'success' : 'muted' }}">
                                {{ $server->is_active ? 'Active' : 'Inactive' }}
                            </flux:badge>
                            <flux:badge variant="outline">
                                {{ $server->auth_type === 'key' ? 'Key Auth' : 'Password' }}
                            </flux:badge>
                        </div>

                        <div class="mt-2 space-y-1">
                            <flux:text size="sm" class="text-gray-600 dark:text-gray-400">
                                <flux:icon name="server" class="inline w-4 h-4 mr-1" />
                                {{ $server->getConnectionString() }}
                            </flux:text>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <flux:button wire:click="testConnection({{ $server->id }})" variant="outline" size="sm">
                            <flux:icon name="wifi" class="mr-1" />
                            Test
                        </flux:button>

                        <flux:button wire:click="connectToServer({{ $server->id }})" variant="primary" size="sm">
                            <flux:icon name="power" class="mr-1" />
                            Connect
                        </flux:button>

                        <flux:button wire:click="editServer({{ $server->id }})" variant="outline" size="sm">
                            <flux:icon name="pencil" class="mr-1" />
                            Edit
                        </flux:button>

                        <flux:button wire:click="deleteServer({{ $server->id }})"
                            wire:confirm="Are you sure you want to delete this server?" variant="danger" size="sm">
                            <flux:icon name="trash" class="mr-1" />
                            Delete
                        </flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <flux:icon name="server" class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                <flux:heading size="lg" class="text-gray-600 dark:text-gray-400 mb-2">No servers found
                </flux:heading>
                <flux:text class="text-gray-500 dark:text-gray-500 mb-4">
                    {{ $search ? 'No servers match your search.' : 'Add your first SSH server to get started.' }}
                </flux:text>
                @if (!$search)
                    <flux:button wire:click="addServer" variant="primary">
                        Add Your First Server
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
