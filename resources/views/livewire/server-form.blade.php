<div class="space-y-6">
    <form wire:submit="save">
        <div class="space-y-5">
            <!-- Hostname -->
            <div class="space-y-1">
                <div class="relative group">
                    <input wire:model="host" type="text" placeholder="Hostname or IP Address"
                        class="w-full px-4 py-3.5 bg-[#2c2c2e] border border-transparent rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner"
                        required />
                    <div class="absolute inset-0 rounded-xl ring-1 ring-white/5 pointer-events-none"></div>
                </div>
                @error('host') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Port -->
            <div class="space-y-1">
                <div class="relative group">
                    <input wire:model="port" type="number" placeholder="Port (Default: 22)"
                        class="w-full px-4 py-3.5 bg-[#2c2c2e] border border-transparent rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner" />
                    <div class="absolute inset-0 rounded-xl ring-1 ring-white/5 pointer-events-none"></div>
                </div>
                @error('port') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Username -->
            <div class="space-y-1">
                <div class="relative group">
                    <input wire:model="username" type="text" placeholder="Username"
                        class="w-full px-4 py-3.5 bg-[#2c2c2e] border border-transparent rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner"
                        required />
                    <div class="absolute inset-0 rounded-xl ring-1 ring-white/5 pointer-events-none"></div>
                </div>
                @error('username') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Server Name (Optional) -->
            <div class="space-y-1">
                <div class="relative group">
                    <input wire:model="name" type="text" placeholder="Display Name (Optional)"
                        class="w-full px-4 py-3.5 bg-[#2c2c2e] border border-transparent rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner" />
                    <div class="absolute inset-0 rounded-xl ring-1 ring-white/5 pointer-events-none"></div>
                </div>
            </div>

            <!-- Authentication Type -->
            <div class="space-y-2 pt-2">
                <label class="text-sm font-medium text-zinc-400 ml-1">Authentication Type</label>
                <div class="grid grid-cols-2 p-1 bg-[#2c2c2e] rounded-xl">
                    <button type="button" wire:click="$set('auth_type', 'password')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $auth_type === 'password' ? 'bg-[#3a3a3c] text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-300' }}">
                        Password
                    </button>
                    <button type="button" wire:click="$set('auth_type', 'key')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $auth_type === 'key' ? 'bg-blue-600 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-300' }}">
                        Key File
                    </button>
                </div>
            </div>

            <!-- Auth Fields -->
            @if ($auth_type === 'password')
                <div class="space-y-1 animate-fade-in">
                    <div class="relative group">
                        <input wire:model="password" type="password" placeholder="Password"
                            class="w-full px-4 py-3.5 bg-[#2c2c2e] border border-transparent rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner" />
                        <div class="absolute inset-0 rounded-xl ring-1 ring-white/5 pointer-events-none"></div>
                    </div>
                </div>
            @else
                <div class="space-y-1 animate-fade-in">
                    <div class="relative group">
                        <textarea wire:model="private_key" rows="4" placeholder="Paste Private Key"
                            class="w-full px-4 py-3.5 bg-[#2c2c2e] border border-transparent rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner font-mono text-sm"></textarea>
                        <div class="absolute inset-0 rounded-xl ring-1 ring-white/5 pointer-events-none"></div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-6 space-y-4">
            <button type="submit"
                class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(37,99,235,0.3)] transition-all hover:shadow-[0_0_30px_rgba(37,99,235,0.5)] active:scale-[0.98]">
                {{ $server ? 'Save Changes' : 'Connect' }}
            </button>
            
            <div class="flex items-center justify-center gap-2">
                <div class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $is_active ? 'bg-blue-600' : 'bg-zinc-700' }}"
                     wire:click="$toggle('is_active')">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </div>
                <span class="text-sm text-zinc-400">Save for later</span>
            </div>
        </div>
    </form>
</div>
