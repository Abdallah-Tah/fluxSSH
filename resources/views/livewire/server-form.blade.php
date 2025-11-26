<div class="space-y-8">
    <div class="text-center sm:text-left">
        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">{{ $server ? 'Edit Connection' : 'New Connection' }}</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">Configure the details for your secure shell connection.</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Server Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-zinc-900 dark:text-white">Server Name</label>
            <input wire:model="name" type="text" id="name" placeholder="My Production Server"
                class="mt-2 w-full rounded-lg border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-transparent">
            @error('name') <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Hostname -->
        <div>
            <label for="host" class="block text-sm font-medium text-zinc-900 dark:text-white">Hostname / IP</label>
            <input wire:model="host" type="text" id="host" placeholder="192.168.1.1"
                class="mt-2 w-full rounded-lg border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-mono focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-transparent">
            @error('host') <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Port -->
            <div>
                <label for="port" class="block text-sm font-medium text-zinc-900 dark:text-white">Port</label>
                <input wire:model="port" type="number" id="port" placeholder="22"
                    class="mt-2 w-full rounded-lg border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-mono focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-transparent">
                @error('port') <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-zinc-900 dark:text-white">Username</label>
                <input wire:model="username" type="text" id="username" placeholder="root"
                    class="mt-2 w-full rounded-lg border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-mono focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-transparent">
                @error('username') <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Authentication Type -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-zinc-900 dark:text-white">Authentication Type</label>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" wire:click="$set('auth_type', 'password')"
                    class="px-4 py-3 rounded-xl border transition-all {{ $auth_type === 'password' ? 'bg-zinc-900 dark:bg-white/10 border-emerald-500 text-white font-semibold' : 'bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Password
                    </div>
                </button>
                <button type="button" wire:click="$set('auth_type', 'key')"
                    class="px-4 py-3 rounded-xl border transition-all {{ $auth_type === 'key' ? 'bg-zinc-900 dark:bg-white/10 border-emerald-500 text-white font-semibold' : 'bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                        SSH Key
                    </div>
                </button>
            </div>
            @error('auth_type') <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Authentication Input -->
        @if ($auth_type === 'key')
            <div class="space-y-2">
                <label for="private_key" class="block text-sm font-medium text-zinc-900 dark:text-white">Private Key</label>
                <textarea
                    wire:model="private_key"
                    id="private_key"
                    rows="8"
                    placeholder="-----BEGIN OPENSSH PRIVATE KEY-----&#10;b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAA...&#10;-----END OPENSSH PRIVATE KEY-----"
                    class="w-full rounded-lg border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-transparent"
                ></textarea>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Paste your SSH private key (RSA, ED25519, ECDSA, or DSA format)</p>
                @error('private_key') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror

                @if($server && !$private_key)
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm text-blue-700 dark:text-blue-300">Private key is already stored. Leave empty to keep the existing key.</p>
                    </div>
                @endif
            </div>
        @else
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-zinc-900 dark:text-white">Password</label>
                <input wire:model="password" type="password" id="password" placeholder="Enter password"
                    class="w-full rounded-lg border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-transparent">
                @error('password') <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> @enderror

                @if($server && !$password)
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm text-blue-700 dark:text-blue-300">Password is already stored. Leave empty to keep the existing password.</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <flux:button type="submit" variant="primary" class="flex-1">
                <span wire:loading.remove wire:target="save">
                    {{ $server ? 'Update Connection' : 'Create Connection' }}
                </span>
                <span wire:loading wire:target="save">
                    Saving...
                </span>
            </flux:button>

            @if($server)
                <flux:button type="button" wire:click="cancel" variant="ghost">
                    Cancel
                </flux:button>
            @endif
        </div>
    </form>

    <!-- Success Message -->
    @if (session('message'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg">
            <p class="text-sm text-emerald-700 dark:text-emerald-300">{{ session('message') }}</p>
        </div>
    @endif
</div>
