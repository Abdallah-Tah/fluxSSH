<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <flux:heading size="xl" class="font-bold tracking-tight">Dashboard</flux:heading>
                <flux:text class="mt-1 text-gray-500 dark:text-gray-400">Welcome back to your FluxSSH control center.</flux:text>
            </div>
            <div class="flex items-center gap-3">
                 <flux:button href="{{ route('servers') }}" variant="primary" icon="plus">Add Server</flux:button>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- SSH Servers Card -->
            <div class="group relative overflow-hidden bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 transition-all hover:shadow-lg hover:border-blue-500/30 dark:hover:border-blue-500/30">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <flux:icon name="server" class="w-24 h-24 text-blue-500 transform rotate-12 translate-x-4 -translate-y-4" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <flux:icon name="server" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <flux:heading size="lg">SSH Servers</flux:heading>
                    </div>
                    <flux:text class="text-gray-600 dark:text-gray-400 mb-6 min-h-[3rem]">
                        Manage your SSH server connections and access remote terminals securely.
                    </flux:text>
                    <flux:button href="{{ route('servers') }}" variant="outline" class="w-full group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:border-blue-200 dark:group-hover:border-blue-800 transition-colors">
                        Manage Servers
                    </flux:button>
                </div>
            </div>

            <!-- Quick Connect Card -->
            <div class="group relative overflow-hidden bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 transition-all hover:shadow-lg hover:border-green-500/30 dark:hover:border-green-500/30">
                 <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <flux:icon name="power" class="w-24 h-24 text-green-500 transform rotate-12 translate-x-4 -translate-y-4" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                         <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <flux:icon name="power" class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                        <flux:heading size="lg">Quick Connect</flux:heading>
                    </div>
                    <flux:text class="text-gray-600 dark:text-gray-400 mb-6 min-h-[3rem]">
                        Access your most recently used servers instantly with one click.
                    </flux:text>
                    <livewire:quick-connect />
                </div>
            </div>

            <!-- Status Card -->
            <div class="group relative overflow-hidden bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 transition-all hover:shadow-lg hover:border-purple-500/30 dark:hover:border-purple-500/30">
                 <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <flux:icon name="chart-bar" class="w-24 h-24 text-purple-500 transform rotate-12 translate-x-4 -translate-y-4" />
                </div>
                <div class="relative z-10">
                     <div class="flex items-center gap-3 mb-4">
                         <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <flux:icon name="chart-bar" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <flux:heading size="lg">Status</flux:heading>
                    </div>
                    <div class="space-y-4 mb-2">
                        @php
                            $totalServers = \App\Models\Server::count();
                            $activeServers = \App\Models\Server::where('is_active', true)->count();
                        @endphp
                        <div class="flex justify-between items-center p-2 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <flux:text class="text-gray-600 dark:text-gray-400">Total Servers</flux:text>
                            <flux:badge variant="pill">{{ $totalServers }}</flux:badge>
                        </div>
                        <div class="flex justify-between items-center p-2 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <flux:text class="text-gray-600 dark:text-gray-400">Active Servers</flux:text>
                            <flux:badge variant="success" class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">{{ $activeServers }}</flux:badge>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Getting Started -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <flux:icon name="rocket-launch" class="w-5 h-5 text-gray-700 dark:text-gray-300" />
                </div>
                <flux:heading size="lg">Getting Started</flux:heading>
            </div>
            
            <div class="grid gap-4 md:grid-cols-3">
                <div class="group flex flex-col p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <flux:icon name="plus-circle" class="w-5 h-5 text-blue-500" />
                        <span class="font-medium text-gray-900 dark:text-white">Add Server</span>
                    </div>
                    <flux:text class="text-sm mb-4 flex-1">Connect your first SSH server to start managing it.</flux:text>
                    <flux:button href="{{ route('servers') }}" size="sm" variant="primary" class="w-full justify-center">
                        Add Server
                    </flux:button>
                </div>

                <div class="group flex flex-col p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-transparent hover:border-green-200 dark:hover:border-green-800 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <flux:icon name="shield-check" class="w-5 h-5 text-green-500" />
                        <span class="font-medium text-gray-900 dark:text-white">Secure Auth</span>
                    </div>
                    <flux:text class="text-sm mb-4 flex-1">Configure password or SSH key authentication.</flux:text>
                     <div class="mt-auto pt-2 text-xs text-gray-400">
                        Recommended
                    </div>
                </div>

                <div class="group flex flex-col p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-transparent hover:border-purple-200 dark:hover:border-purple-800 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <flux:icon name="command-line" class="w-5 h-5 text-purple-500" />
                        <span class="font-medium text-gray-900 dark:text-white">Execute</span>
                    </div>
                    <flux:text class="text-sm mb-4 flex-1">Run commands directly from your browser.</flux:text>
                    <div class="mt-auto pt-2 text-xs text-gray-400">
                        Terminal access
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
