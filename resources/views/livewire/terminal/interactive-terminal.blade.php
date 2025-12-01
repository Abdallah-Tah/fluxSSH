<div class="h-full flex flex-col" wire:ignore.self>
    {{-- Terminal Header --}}
    <div class="flex items-center justify-between bg-slate-900 border-b border-slate-700 px-4 py-2 rounded-t-lg">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-full {{ $connected ? 'bg-green-500' : 'bg-red-500' }}"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-slate-600"></div>
            </div>
            <span class="text-sm font-medium text-slate-300">
                {{ $server->username }}@{{ $server->host }}:{{ $server->port }}
            </span>

            {{-- @if ($connected) --}}
            <span class="px-2 py-0.5 text-xs font-medium bg-green-500/20 text-green-400 rounded-full">
                Connected
            </span>
            {{-- @endif --}}
        </div>
        <div class="flex items-center gap-2">
            @if (!$connected)
                <button wire:click="connect"
                    class="px-3 py-1.5 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                    Connect
                </button>
            @else
                <button wire:click="disconnect"
                    class="px-3 py-1.5 text-xs font-medium bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors">
                    Disconnect
                </button>
            @endif
        </div>
    </div>

    {{-- Error Message --}}
    @if ($error)
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-2 text-sm">
            {{ $error }}
        </div>
    @endif

    {{-- Terminal Container --}}
    <div id="terminal-container" class="flex-1 bg-black rounded-b-lg overflow-hidden" wire:ignore>
        <div id="terminal" class="h-full w-full"></div>
    </div>
</div>

@assets
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@xterm/xterm@5.5.0/css/xterm.css" />
    <script src="https://cdn.jsdelivr.net/npm/@xterm/xterm@5.5.0/lib/xterm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@xterm/addon-fit@0.10.0/lib/addon-fit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@xterm/addon-web-links@0.11.0/lib/addon-web-links.min.js"></script>
@endassets

@script
    <script>
        let terminal = null;
        let fitAddon = null;
        let isConnected = false;
        let commandBuffer = '';
        let commandHistory = [];
        let historyIndex = -1;

        function initTerminal() {
            if (terminal) {
                terminal.dispose();
            }

            terminal = new Terminal({
                cursorBlink: true,
                cursorStyle: 'block',
                fontSize: 14,
                fontFamily: 'JetBrains Mono, Menlo, Monaco, Consolas, monospace',
                theme: {
                    background: '#0f172a',
                    foreground: '#e2e8f0',
                    cursor: '#22c55e',
                    cursorAccent: '#0f172a',
                    selectionBackground: '#334155',
                    black: '#1e293b',
                    red: '#ef4444',
                    green: '#22c55e',
                    yellow: '#eab308',
                    blue: '#3b82f6',
                    magenta: '#a855f7',
                    cyan: '#06b6d4',
                    white: '#f1f5f9',
                    brightBlack: '#475569',
                    brightRed: '#f87171',
                    brightGreen: '#4ade80',
                    brightYellow: '#facc15',
                    brightBlue: '#60a5fa',
                    brightMagenta: '#c084fc',
                    brightCyan: '#22d3ee',
                    brightWhite: '#ffffff'
                },
                allowProposedApi: true,
                scrollback: 10000,
                convertEol: true
            });

            fitAddon = new FitAddon.FitAddon();
            terminal.loadAddon(fitAddon);
            terminal.loadAddon(new WebLinksAddon.WebLinksAddon());

            const container = document.getElementById('terminal');
            terminal.open(container);
            fitAddon.fit();

            // Handle keyboard input
            terminal.onData(data => {
                if (!isConnected) return;

                // Handle special keys
                if (data === '\r') {
                    // Enter key - execute command
                    terminal.write('\r\n');
                    if (commandBuffer.trim()) {
                        commandHistory.push(commandBuffer);
                        historyIndex = commandHistory.length;
                        $wire.executeCommand(commandBuffer);
                    } else {
                        // Empty command, just show prompt again
                        $wire.executeCommand('');
                    }
                    commandBuffer = '';
                } else if (data === '\x7f' || data === '\b') {
                    // Backspace
                    if (commandBuffer.length > 0) {
                        commandBuffer = commandBuffer.slice(0, -1);
                        terminal.write('\b \b');
                    }
                } else if (data === '\x03') {
                    // Ctrl+C
                    terminal.write('^C\r\n');
                    commandBuffer = '';
                    $wire.executeCommand('');
                } else if (data === '\x1b[A') {
                    // Up arrow - history
                    if (historyIndex > 0) {
                        historyIndex--;
                        // Clear current line
                        terminal.write('\r\x1b[K');
                        // Get command from history
                        commandBuffer = commandHistory[historyIndex] || '';
                        // We need to redraw the prompt + command
                        // For simplicity, just show the command
                        terminal.write(commandBuffer);
                    }
                } else if (data === '\x1b[B') {
                    // Down arrow - history
                    if (historyIndex < commandHistory.length - 1) {
                        historyIndex++;
                        terminal.write('\r\x1b[K');
                        commandBuffer = commandHistory[historyIndex] || '';
                        terminal.write(commandBuffer);
                    } else if (historyIndex === commandHistory.length - 1) {
                        historyIndex = commandHistory.length;
                        terminal.write('\r\x1b[K');
                        commandBuffer = '';
                    }
                } else if (data.charCodeAt(0) >= 32) {
                    // Regular printable character
                    commandBuffer += data;
                    terminal.write(data);
                }
            });

            // Handle resize
            const resizeObserver = new ResizeObserver(() => {
                if (fitAddon) {
                    fitAddon.fit();
                }
            });
            resizeObserver.observe(container);

            terminal.write('\x1b[1;32mFluxSSH Terminal\x1b[0m\r\n');
            terminal.write('\x1b[90mClick "Connect" to start SSH session\x1b[0m\r\n\r\n');
            terminal.focus();
        }

        // Handle Livewire events
        Livewire.on('terminal-connected', () => {
            isConnected = true;
            terminal.clear();
            commandBuffer = '';
            commandHistory = [];
            historyIndex = -1;
            terminal.focus();
        });

        Livewire.on('terminal-output', (event) => {
            if (terminal && event.output) {
                terminal.write(event.output);
            }
        });

        Livewire.on('terminal-error', (event) => {
            isConnected = false;
            terminal.write('\r\n\x1b[1;31mError: ' + event.error + '\x1b[0m\r\n');
        });

        Livewire.on('terminal-disconnected', () => {
            isConnected = false;
            terminal.write('\r\n\x1b[1;33mDisconnected\x1b[0m\r\n');
            terminal.write('\x1b[90mClick "Connect" to reconnect\x1b[0m\r\n');
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(initTerminal, 100);
        });

        // Re-initialize if terminal container exists
        if (document.getElementById('terminal')) {
            initTerminal();
        }
    </script>
@endscript
