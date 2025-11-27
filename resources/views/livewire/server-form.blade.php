<div class="space-y-6">
    <div class="text-center sm:text-left">
        <h2 class="text-xl font-bold text-text-primary tracking-tight">{{ $server ? 'Edit Connection' : 'New Connection' }}</h2>
        <p class="mt-1 text-sm text-text-secondary">Configure the details for your secure shell connection.</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Server Name -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-text-primary">Server Name</label>
            <input wire:model="name" type="text" id="name" placeholder="e.g. Production DB"
                class="w-full rounded-md border-border-strong bg-bg-surface text-text-primary placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors">
            @error('name') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Connection Details -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-8 space-y-2">
                <label for="host" class="block text-sm font-medium text-text-primary">Hostname / IP</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-text-tertiary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                        </svg>
                    </div>
                    <input wire:model="host" type="text" id="host" placeholder="192.168.1.1"
                        class="w-full rounded-md border-border-strong bg-bg-surface pl-10 text-text-primary font-mono placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors">
                </div>
                @error('host') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-4 space-y-2">
                <label for="port" class="block text-sm font-medium text-text-primary">Port</label>
                <input wire:model="port" type="number" id="port" placeholder="22"
                    class="w-full rounded-md border-border-strong bg-bg-surface text-text-primary font-mono placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors">
                @error('port') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Username -->
        <div class="space-y-2">
            <label for="username" class="block text-sm font-medium text-text-primary">Username</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-text-tertiary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <input wire:model="username" type="text" id="username" placeholder="root"
                    class="w-full rounded-md border-border-strong bg-bg-surface pl-10 text-text-primary font-mono placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors">
            </div>
            @error('username') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Authentication Type -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-text-primary">Authentication Method</label>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" wire:click="$set('auth_type', 'password')"
                    class="relative flex items-center justify-center gap-2 px-4 py-3 rounded-lg border transition-all {{ $auth_type === 'password' ? 'bg-bg-surface-alt border-primary-500 text-primary-600 ring-1 ring-primary-500' : 'bg-bg-surface border-border-strong text-text-secondary hover:border-border-subtle hover:bg-bg-surface-alt' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span class="font-medium text-sm">Password</span>
                </button>
                <button type="button" wire:click="$set('auth_type', 'key')"
                    class="relative flex items-center justify-center gap-2 px-4 py-3 rounded-lg border transition-all {{ $auth_type === 'key' ? 'bg-bg-surface-alt border-primary-500 text-primary-600 ring-1 ring-primary-500' : 'bg-bg-surface border-border-strong text-text-secondary hover:border-border-subtle hover:bg-bg-surface-alt' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                    <span class="font-medium text-sm">SSH Key</span>
                </button>
            </div>
            @error('auth_type') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Authentication Input -->
        @if ($auth_type === 'key')
            <div class="space-y-2">
                <label for="private_key" class="block text-sm font-medium text-text-primary">Private Key</label>
                <textarea
                    wire:model="private_key"
                    id="private_key"
                    rows="6"
                    placeholder="-----BEGIN OPENSSH PRIVATE KEY-----&#10;..."
                    class="w-full rounded-md border-border-strong bg-bg-code text-zinc-300 font-mono text-xs focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors"
                ></textarea>
                <p class="text-xs text-text-tertiary">Paste your SSH private key (RSA, ED25519, ECDSA, or DSA).</p>
                @error('private_key') <span class="text-xs text-danger">{{ $message }}</span> @enderror

                @if($server && !$private_key)
                    <div class="p-3 bg-blue-500/10 border border-blue-500/20 rounded-md">
                        <p class="text-xs text-blue-600 dark:text-blue-400">Private key is already stored. Leave empty to keep existing.</p>
                    </div>
                @endif
            </div>
        @else
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-text-primary">Password</label>
                <input wire:model="password" type="password" id="password" placeholder="••••••••"
                    class="w-full rounded-md border-border-strong bg-bg-surface text-text-primary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors">
                @error('password') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror

                @if($server && !$password)
                    <div class="p-3 bg-blue-500/10 border border-blue-500/20 rounded-md">
                        <p class="text-xs text-blue-600 dark:text-blue-400">Password is already stored. Leave empty to keep existing.</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 flex justify-center items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-md shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save">
                    {{ $server ? 'Update Connection' : 'Connect Server' }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>

            @if($server)
                <button type="button" wire:click="cancel" class="px-4 py-2 bg-bg-surface border border-border-strong hover:bg-bg-surface-alt text-text-secondary text-sm font-medium rounded-md transition-colors">
                    Cancel
                </button>
            @endif
        </div>
    </form>

    <!-- Success Message -->
    @if (session('message'))
        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-md">
            <p class="text-sm text-emerald-600 dark:text-emerald-400">{{ session('message') }}</p>
        </div>
    @endif
</div>
