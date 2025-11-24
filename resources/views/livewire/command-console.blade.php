<div class="h-screen flex flex-col bg-zinc-950 text-zinc-100 font-mono">
    <!-- Header -->
    <div class="bg-zinc-900 border-b border-zinc-800 p-4 flex justify-between items-center shrink-0">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-3">
                <flux:icon name="server" class="size-5 text-emerald-500" />
                <flux:heading size="lg" class="text-white">{{ $server->name }}</flux:heading>
            </div>
            <flux:badge variant="{{ $connected ? 'success' : 'danger' }}" size="sm">
                {{ $connected ? 'Connected' : 'Disconnected' }}
            </flux:badge>
            <flux:text class="text-zinc-400 text-sm">
                {{ $server->getConnectionString() }}
            </flux:text>
        </div>

        <div class="flex items-center gap-2">
            <flux:button wire:click="clearOutput" variant="ghost" size="sm">
                <flux:icon name="trash" class="size-4" />
                Clear
            </flux:button>

            <flux:button wire:click="initializeConnection" variant="ghost" size="sm" :disabled="$isLoading">
                <flux:icon name="arrow-path" class="size-4" />
                Reconnect
            </flux:button>

            <a href="{{ route('servers') }}">
                <flux:button variant="ghost" size="sm">
                    <flux:icon name="x-mark" class="size-4" />
                    Close
                </flux:button>
            </a>
        </div>
    </div>

    <!-- Terminal Output -->
    <div class="flex-1 overflow-y-auto p-6 space-y-1 min-h-0 bg-zinc-950"
         x-data="{
            scrollToBottom() {
                this.$el.scrollTop = this.$el.scrollHeight;
            }
         }"
         x-init="scrollToBottom()"
         x-effect="scrollToBottom()">
        @forelse ($output as $line)
            <div class="flex gap-3 py-0.5 hover:bg-zinc-900/50 -mx-2 px-2 rounded transition-colors">
                <span class="text-zinc-600 text-xs font-normal flex-shrink-0 pt-0.5 w-16">{{ $line['timestamp'] }}</span>
                <pre class="{{ $this->getLineClass($line['type']) }} whitespace-pre-wrap break-words flex-1 font-mono text-sm">{{ $line['text'] }}</pre>
            </div>
        @empty
            <div class="text-center py-12">
                <flux:icon name="command-line" class="size-12 text-zinc-700 mx-auto mb-3" />
                <flux:heading size="lg" class="text-zinc-400 mb-2">FluxSSH Terminal</flux:heading>
                <flux:text class="text-zinc-600">
                    {{ $connected ? 'Type a command to get started...' : 'Connecting to server...' }}
                </flux:text>
            </div>
        @endforelse

        @if ($isLoading)
            <div class="flex items-center gap-2 text-amber-400 py-1">
                <div class="animate-spin rounded-full size-3 border-2 border-amber-400 border-t-transparent"></div>
                <span class="text-sm">Executing...</span>
            </div>
        @endif
    </div>

    <!-- Command Input -->
    <div class="bg-zinc-900 border-t border-zinc-800 p-4 shrink-0">
        @if ($connected)
            <form wire:submit="executeCommand" class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-emerald-500 flex-shrink-0">
                    <flux:icon name="chevron-right" class="size-4" />
                    <span class="text-sm font-medium">{{ $server->username }}@{{ $server->host }}</span>
                </div>
                <input
                    wire:model="command"
                    type="text"
                    placeholder="Type a command and press Enter..."
                    class="flex-1 bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-zinc-100 placeholder-zinc-600 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500"
                    autocomplete="off"
                    x-data="commandHistory"
                    x-on:keydown.arrow-up="previousCommand()"
                    x-on:keydown.arrow-down="nextCommand()"
                    x-ref="commandInput"
                    :disabled="$wire.isLoading" />
                <flux:button type="submit" variant="primary" size="sm" :disabled="!$connected || $isLoading">
                    <flux:icon name="paper-airplane" class="size-4" />
                    Send
                </flux:button>
            </form>
            <div class="flex items-center gap-4 mt-3 text-xs text-zinc-500">
                <div class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 bg-zinc-800 rounded border border-zinc-700">↑</kbd>
                    <kbd class="px-1.5 py-0.5 bg-zinc-800 rounded border border-zinc-700">↓</kbd>
                    <span>History</span>
                </div>
                <div class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 bg-zinc-800 rounded border border-zinc-700">Enter</kbd>
                    <span>Execute</span>
                </div>
            </div>
        @else
            <div class="text-center py-4">
                <flux:text class="text-zinc-500 mb-3">Not connected to server</flux:text>
                <flux:button wire:click="initializeConnection" variant="primary" size="sm">
                    <flux:icon name="bolt" class="size-4" />
                    Connect Now
                </flux:button>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('commandHistory', () => ({
            historyIndex: -1,
            tempCommand: '',

            init() {
                this.$watch('$wire.command', () => {
                    this.historyIndex = -1;
                });

                // Focus input on mount
                this.$nextTick(() => {
                    this.$refs.commandInput.focus();
                });
            },

            previousCommand() {
                const history = @json($this->getCommandHistory());
                if (history.length === 0) return;

                if (this.historyIndex === -1) {
                    this.tempCommand = this.$wire.command;
                    this.historyIndex = 0;
                } else if (this.historyIndex < history.length - 1) {
                    this.historyIndex++;
                }

                this.$wire.set('command', history[this.historyIndex]);
            },

            nextCommand() {
                const history = @json($this->getCommandHistory());
                if (this.historyIndex === -1) return;

                if (this.historyIndex > 0) {
                    this.historyIndex--;
                    this.$wire.set('command', history[this.historyIndex]);
                } else {
                    this.historyIndex = -1;
                    this.$wire.set('command', this.tempCommand);
                }
            }
        }));
    });

    // Focus input after command execution
    document.addEventListener('livewire:init', () => {
        Livewire.on('focusInput', () => {
            setTimeout(() => {
                document.querySelector('input[wire\\:model="command"]')?.focus();
            }, 100);
        });
    });
</script>
