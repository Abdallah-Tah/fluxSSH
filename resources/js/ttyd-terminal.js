import { Terminal } from '@xterm/xterm';
import { FitAddon } from '@xterm/addon-fit';
import { WebLinksAddon } from '@xterm/addon-web-links';

/**
 * TtydTerminal - A client for ttyd WebSocket terminal
 *
 * ttyd protocol:
 * - Client sends: 0 + input data (input)
 * - Client sends: 1 + JSON {columns, rows} (resize)
 * - Server sends: 0 + output data (output)
 * - Server sends: 1 + window title (set title)
 */
export class TtydTerminal {
    constructor(container, options = {}) {
        this.container = container;
        this.options = options;
        this.ws = null;
        this.terminal = null;
        this.fitAddon = null;
        this.connected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.onStatusChange = options.onStatusChange || (() => {});

        this.init();
    }

    init() {
        // Create terminal with ttyd-compatible settings
        this.terminal = new Terminal({
            cursorBlink: true,
            cursorStyle: 'block',
            fontFamily: '"Cascadia Code", "Fira Code", Menlo, Monaco, "Courier New", monospace',
            fontSize: 14,
            lineHeight: 1.2,
            letterSpacing: 0,
            theme: {
                background: '#1e1e1e',
                foreground: '#d4d4d4',
                cursor: '#d4d4d4',
                cursorAccent: '#1e1e1e',
                selectionBackground: 'rgba(255, 255, 255, 0.3)',
                black: '#000000',
                red: '#cd3131',
                green: '#0dbc79',
                yellow: '#e5e510',
                blue: '#2472c8',
                magenta: '#bc3fbc',
                cyan: '#11a8cd',
                white: '#e5e5e5',
                brightBlack: '#666666',
                brightRed: '#f14c4c',
                brightGreen: '#23d18b',
                brightYellow: '#f5f543',
                brightBlue: '#3b8eea',
                brightMagenta: '#d670d6',
                brightCyan: '#29b8db',
                brightWhite: '#e5e5e5'
            },
            allowProposedApi: true,
            scrollback: 10000,
        });

        // Add fit addon
        this.fitAddon = new FitAddon();
        this.terminal.loadAddon(this.fitAddon);

        // Add web links addon
        this.terminal.loadAddon(new WebLinksAddon());

        // Open terminal in container
        this.terminal.open(this.container);

        // Fit to container
        this.fit();

        // Handle terminal input
        this.terminal.onData((data) => {
            this.sendInput(data);
        });

        // Handle resize
        this.terminal.onResize(({ cols, rows }) => {
            this.sendResize(cols, rows);
        });

        // Handle window resize
        window.addEventListener('resize', () => this.fit());

        // Focus terminal
        this.terminal.focus();
    }

    fit() {
        if (this.fitAddon) {
            try {
                this.fitAddon.fit();
            } catch (e) {
                console.warn('Failed to fit terminal:', e);
            }
        }
    }

    connect(wsUrl) {
        this.wsUrl = wsUrl;
        this.onStatusChange('connecting', 'Connecting...');

        try {
            this.ws = new WebSocket(wsUrl, ['tty']);

            this.ws.binaryType = 'arraybuffer';

            this.ws.onopen = () => {
                this.connected = true;
                this.reconnectAttempts = 0;
                this.onStatusChange('connected', 'Connected');

                // Send initial resize
                setTimeout(() => {
                    this.fit();
                    this.sendResize(this.terminal.cols, this.terminal.rows);
                }, 100);
            };

            this.ws.onmessage = (event) => {
                this.handleMessage(event.data);
            };

            this.ws.onclose = (event) => {
                this.connected = false;
                console.log('WebSocket closed:', event.code, event.reason);

                if (event.code !== 1000 && this.reconnectAttempts < this.maxReconnectAttempts) {
                    this.onStatusChange('reconnecting', `Reconnecting (${this.reconnectAttempts + 1}/${this.maxReconnectAttempts})...`);
                    this.reconnectAttempts++;
                    setTimeout(() => this.connect(this.wsUrl), 2000);
                } else {
                    this.onStatusChange('disconnected', 'Disconnected');
                    this.terminal.write('\r\n\x1b[31mConnection closed.\x1b[0m\r\n');
                }
            };

            this.ws.onerror = (error) => {
                console.error('WebSocket error:', error);
                this.onStatusChange('error', 'Connection error');
            };

        } catch (error) {
            console.error('Failed to connect:', error);
            this.onStatusChange('error', 'Failed to connect');
        }
    }

    handleMessage(data) {
        if (data instanceof ArrayBuffer) {
            const view = new Uint8Array(data);
            const cmd = view[0];
            const payload = view.slice(1);

            switch (cmd) {
                case 0: // Output
                    const text = new TextDecoder().decode(payload);
                    this.terminal.write(text);
                    break;
                case 1: // Set window title
                    const title = new TextDecoder().decode(payload);
                    if (this.options.onTitleChange) {
                        this.options.onTitleChange(title);
                    }
                    break;
                case 2: // Set preferences (ignore)
                    break;
                default:
                    console.warn('Unknown command:', cmd);
            }
        }
    }

    sendInput(data) {
        if (this.ws && this.ws.readyState === WebSocket.OPEN) {
            const encoder = new TextEncoder();
            const payload = encoder.encode(data);
            const buffer = new Uint8Array(1 + payload.length);
            buffer[0] = 0; // Input command
            buffer.set(payload, 1);
            this.ws.send(buffer);
        }
    }

    sendResize(cols, rows) {
        if (this.ws && this.ws.readyState === WebSocket.OPEN) {
            const data = JSON.stringify({ columns: cols, rows: rows });
            const encoder = new TextEncoder();
            const payload = encoder.encode(data);
            const buffer = new Uint8Array(1 + payload.length);
            buffer[0] = 1; // Resize command
            buffer.set(payload, 1);
            this.ws.send(buffer);
        }
    }

    disconnect() {
        if (this.ws) {
            this.ws.close(1000, 'User disconnect');
            this.ws = null;
        }
    }

    dispose() {
        this.disconnect();
        if (this.terminal) {
            this.terminal.dispose();
        }
    }

    write(data) {
        if (this.terminal) {
            this.terminal.write(data);
        }
    }

    focus() {
        if (this.terminal) {
            this.terminal.focus();
        }
    }
}

// Export for global use
window.TtydTerminal = TtydTerminal;
