<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal - {{ $server->name }}</title>
    <style>
        :root {
            /* Saturn Theme (Default) */
            --term-bg: #1a1b26;
            --term-text: #a9b1d6;
            --term-cursor: #c0caf5;
            --term-selection: rgba(130, 170, 255, 0.3);
            --term-black: #414868;
            --term-red: #f7768e;
            --term-green: #9ece6a;
            --term-yellow: #e0af68;
            --term-blue: #7aa2f7;
            --term-magenta: #bb9af7;
            --term-cyan: #7dcfff;
            --term-white: #c0caf5;
            --term-bright-black: #414868;
            --term-bright-red: #f7768e;
            --term-bright-green: #9ece6a;
            --term-bright-yellow: #e0af68;
            --term-bright-blue: #7aa2f7;
            --term-bright-magenta: #bb9af7;
            --term-bright-cyan: #7dcfff;
            --term-bright-white: #c0caf5;

            /* UI Colors */
            --header-bg: #16161e;
            --header-border: #414868;
            --status-success: #9ece6a;
            --status-error: #f7768e;
            --status-warning: #e0af68;
            --button-bg: #414868;
            --button-hover: #565f89;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'JetBrains Mono', 'Fira Code', 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
            background: var(--term-bg);
            color: var(--term-text);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Terminal Header */
        .terminal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: var(--header-bg);
            border-bottom: 1px solid var(--header-border);
            flex-shrink: 0;
        }

        .terminal-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .terminal-icon {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--term-cyan);
        }

        .terminal-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--term-text);
        }

        .terminal-host {
            font-size: 0.75rem;
            color: var(--term-bright-black);
            margin-left: 0.5rem;
        }

        /* Status Indicator */
        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
        }

        .status-dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .status-dot.connecting {
            background: var(--status-warning);
        }

        .status-dot.connected {
            background: var(--status-success);
            animation: none;
        }

        .status-dot.error {
            background: var(--status-error);
            animation: none;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            background: var(--button-bg);
            border: none;
            border-radius: 0.375rem;
            color: var(--term-text);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: var(--button-hover);
            color: var(--term-white);
        }

        .action-btn svg {
            width: 1rem;
            height: 1rem;
        }

        /* Terminal Container */
        .terminal-container {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .terminal-iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: var(--term-bg);
        }

        /* Loading Overlay */
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: var(--term-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            z-index: 10;
            transition: opacity 0.3s ease;
        }

        .loading-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loading-spinner {
            width: 2.5rem;
            height: 2.5rem;
            border: 3px solid var(--term-bright-black);
            border-top-color: var(--term-cyan);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            font-size: 0.875rem;
            color: var(--term-bright-black);
        }

        /* Error State */
        .error-container {
            position: absolute;
            inset: 0;
            background: var(--term-bg);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            z-index: 10;
        }

        .error-container.visible {
            display: flex;
        }

        .error-icon {
            width: 3rem;
            height: 3rem;
            color: var(--status-error);
        }

        .error-message {
            font-size: 0.875rem;
            color: var(--term-text);
            text-align: center;
            max-width: 24rem;
        }

        .retry-btn {
            padding: 0.5rem 1rem;
            background: var(--button-bg);
            border: none;
            border-radius: 0.375rem;
            color: var(--term-text);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .retry-btn:hover {
            background: var(--button-hover);
            color: var(--term-white);
        }

        /* Fullscreen Mode */
        body.fullscreen .terminal-header {
            display: none;
        }

        body.fullscreen .terminal-container {
            height: 100vh;
        }
    </style>
</head>

<body>
    <!-- Terminal Header -->
    <header class="terminal-header">
        <div class="terminal-title">
            <svg class="terminal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="terminal-name">{{ $server->name }}</span>
            <span class="terminal-host">{{ $server->username }}@{{ $server - > host }}</span>
        </div>

        <div class="status-indicator">
            <span class="status-dot connecting" id="statusDot"></span>
            <span id="statusText">Connecting...</span>
        </div>

        <div class="header-actions">
            <button class="action-btn" id="fullscreenBtn" title="Toggle Fullscreen">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
            </button>
            <button class="action-btn" id="reconnectBtn" title="Reconnect">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
            <a href="{{ route('servers.index') }}" class="action-btn" title="Back to Servers">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>
    </header>

    <!-- Terminal Container -->
    <div class="terminal-container">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
            <span class="loading-text">Initializing terminal session...</span>
        </div>

        <!-- Error Container -->
        <div class="error-container" id="errorContainer">
            <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="error-message" id="errorMessage">Failed to connect to terminal session.</p>
            <button class="retry-btn" id="retryBtn">Retry Connection</button>
        </div>

        <!-- Terminal iFrame -->
        <iframe id="terminalFrame" class="terminal-iframe" src="http://localhost:{{ $port }}/"
            style="display: none;"></iframe>
    </div>

    <script>
        const terminalFrame = document.getElementById('terminalFrame');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const errorContainer = document.getElementById('errorContainer');
        const errorMessage = document.getElementById('errorMessage');
        const statusDot = document.getElementById('statusDot');
        const statusText = document.getElementById('statusText');
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        const reconnectBtn = document.getElementById('reconnectBtn');
        const retryBtn = document.getElementById('retryBtn');

        let connectionAttempts = 0;
        const maxAttempts = 10;

        function setStatus(status, text) {
            statusDot.className = 'status-dot ' + status;
            statusText.textContent = text;
        }

        function showError(message) {
            errorMessage.textContent = message;
            errorContainer.classList.add('visible');
            loadingOverlay.classList.add('hidden');
            setStatus('error', 'Disconnected');
        }

        function hideError() {
            errorContainer.classList.remove('visible');
        }

        function checkTerminalReady() {
            connectionAttempts++;

            if (connectionAttempts > maxAttempts) {
                showError('Terminal session timed out. The server may be unavailable.');
                return;
            }

            fetch('http://localhost:{{ $port }}/', {
                    mode: 'no-cors'
                })
                .then(() => {
                    // Terminal is ready
                    terminalFrame.style.display = 'block';
                    loadingOverlay.classList.add('hidden');
                    setStatus('connected', 'Connected');

                    // Focus the iframe
                    setTimeout(() => terminalFrame.focus(), 100);
                })
                .catch(() => {
                    // Not ready yet, retry
                    setTimeout(checkTerminalReady, 500);
                });
        }

        function reconnect() {
            hideError();
            loadingOverlay.classList.remove('hidden');
            terminalFrame.style.display = 'none';
            setStatus('connecting', 'Reconnecting...');
            connectionAttempts = 0;

            // Reload the iframe
            terminalFrame.src = terminalFrame.src;
            checkTerminalReady();
        }

        // Event listeners
        terminalFrame.addEventListener('load', () => {
            loadingOverlay.classList.add('hidden');
            terminalFrame.style.display = 'block';
            setStatus('connected', 'Connected');
        });

        terminalFrame.addEventListener('error', () => {
            showError('Failed to load terminal. Please check the connection.');
        });

        fullscreenBtn.addEventListener('click', () => {
            document.body.classList.toggle('fullscreen');
            terminalFrame.focus();
        });

        reconnectBtn.addEventListener('click', reconnect);
        retryBtn.addEventListener('click', reconnect);

        // Keyboard shortcut for fullscreen
        document.addEventListener('keydown', (e) => {
            if (e.key === 'F11' || (e.ctrlKey && e.shiftKey && e.key === 'F')) {
                e.preventDefault();
                document.body.classList.toggle('fullscreen');
            }
            if (e.key === 'Escape' && document.body.classList.contains('fullscreen')) {
                document.body.classList.remove('fullscreen');
            }
        });

        // Start checking for terminal readiness
        checkTerminalReady();
    </script>
</body>

</html>
