<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-6">
        <flux:heading size="xl">FluxSSH Dashboard</flux:heading>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- SSH Servers Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <flux:icon name="server" class="w-8 h-8 text-blue-500" />
                    <flux:heading size="lg">SSH Servers</flux:heading>
                </div>
                <flux:text class="text-gray-600 dark:text-gray-400 mb-4">
                    Manage your SSH server connections and access remote terminals.
                </flux:text>
                <flux:button href="{{ route('servers') }}" variant="primary" class="w-full">
                    Manage Servers
                </flux:button>
            </div>

            <!-- Quick Connect Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <flux:icon name="power" class="w-8 h-8 text-green-500" />
                    <flux:heading size="lg">Quick Connect</flux:heading>
                </div>
                <flux:text class="text-gray-600 dark:text-gray-400 mb-4">
                    Access your most recently used servers with one click.
                </flux:text>
                <livewire:quick-connect />
            </div>

            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <flux:icon name="chart-bar" class="w-8 h-8 text-purple-500" />
                    <flux:heading size="lg">Connection Status</flux:heading>
                </div>
                <div class="space-y-3">
                    @php
                        $totalServers = \App\Models\Server::count();
                        $activeServers = \App\Models\Server::where('is_active', true)->count();
                    @endphp
                    <div class="flex justify-between">
                        <flux:text class="text-gray-600 dark:text-gray-400">Total Servers:</flux:text>
                        <flux:text class="font-semibold">{{ $totalServers }}</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text class="text-gray-600 dark:text-gray-400">Active Servers:</flux:text>
                        <flux:text class="font-semibold text-green-600">{{ $activeServers }}</flux:text>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <flux:heading size="lg" class="mb-4">Getting Started</flux:heading>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <flux:icon name="plus" class="w-5 h-5 text-blue-500" />
                    <flux:text>Add your first SSH server to get started</flux:text>
                    <flux:button href="{{ route('servers') }}" size="sm" variant="outline" class="ml-auto">
                        Add Server
                    </flux:button>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <flux:icon name="shield-check" class="w-5 h-5 text-green-500" />
                    <flux:text>Configure secure authentication (password or SSH keys)</flux:text>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <flux:icon name="power" class="w-5 h-5 text-purple-500" />
                    <flux:text>Start executing commands on your remote servers</flux:text>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
