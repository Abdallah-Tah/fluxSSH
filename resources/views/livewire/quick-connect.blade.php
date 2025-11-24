<div>
    @if (count($recentServers) > 0)
        <div class="space-y-2">
            @foreach ($recentServers as $server)
                <div
                    class="flex items-center justify-between p-2 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-2 h-2 rounded-full {{ $server['status'] === 'active' ? 'bg-green-500' : 'bg-gray-400' }}">
                            </div>
                            <flux:text class="font-medium truncate">{{ $server['name'] }}</flux:text>
                        </div>
                        <flux:text size="sm" class="text-gray-500 truncate">{{ $server['host'] }}</flux:text>
                        <flux:text size="xs" class="text-gray-400">{{ $server['last_connected'] }}</flux:text>
                    </div>

                    <div class="flex-shrink-0">
                        <flux:button wire:click="quickConnect({{ $server['id'] }})" wire:loading.attr="disabled"
                            wire:target="quickConnect({{ $server['id'] }})" size="sm" variant="outline"
                            class="ml-2">
                            <span wire:loading.remove wire:target="quickConnect({{ $server['id'] }})">
                                <flux:icon name="power" class="w-4 h-4" />
                            </span>
                            <span wire:loading wire:target="quickConnect({{ $server['id'] }})">
                                <flux:icon name="loading" class="w-4 h-4 animate-spin" />
                            </span>
                        </flux:button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <flux:button href="{{ route('servers') }}" variant="outline" size="sm" class="w-full">
                View All Servers
            </flux:button>
        </div>
    @else
        <div class="text-center py-6">
            <flux:icon name="server" class="w-12 h-12 mx-auto text-gray-400 mb-3" />
            <flux:text class="text-gray-500 mb-3">No servers configured yet</flux:text>
            <flux:button href="{{ route('servers') }}" variant="primary" size="sm">
                Add Your First Server
            </flux:button>
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 p-3 bg-red-100 border border-red-200 text-red-700 rounded-lg">
            <flux:text size="sm">{{ session('error') }}</flux:text>
        </div>
    @endif
</div>
