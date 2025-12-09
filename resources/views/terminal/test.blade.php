<x-layouts.app title="Test Terminal - htop Demo">
    <div class="min-h-screen bg-gray-900 p-4">
        <div class="max-w-6xl mx-auto">
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-white">Test Terminal - Interactive Commands Demo</h1>
                <p class="text-gray-400 mt-2">This terminal runs locally on your Mac. Try commands like: htop, vim, nano, top</p>
            </div>

            <div class="bg-black rounded-lg overflow-hidden shadow-2xl border border-gray-700">
                <div class="bg-gray-800 px-4 py-2 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="ml-2 text-sm text-gray-300">Local Bash Terminal</span>
                    </div>
                    <span id="status" class="text-xs text-gray-400">Initializing...</span>
                </div>

                <iframe id="terminal" class="w-full" style="height: 600px; border: none;"></iframe>
            </div>

            <div class="mt-4 bg-gray-800 rounded-lg p-4">
                <h2 class="text-white font-semibold mb-2">Try these commands:</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                    <code class="bg-gray-900 text-green-400 px-2 py-1 rounded">htop</code>
                    <code class="bg-gray-900 text-green-400 px-2 py-1 rounded">top</code>
                    <code class="bg-gray-900 text-green-400 px-2 py-1 rounded">vim test.txt</code>
                    <code class="bg-gray-900 text-green-400 px-2 py-1 rounded">nano file.txt</code>
                    <code class="bg-gray-900 text-green-400 px-2 py-1 rounded">less /etc/hosts</code>
                    <code class="bg-gray-900 text-green-400 px-2 py-1 rounded">ps aux | less</code>
                    <code class="bg-gray-900 text-green-400 px-2 py-1 rounded">df -h</code>
                    <code class="bg-gray-900 text-green-400 px-2 py-1 rounded">ls -la | more</code>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function startTerminal() {
            const status = document.getElementById('status');
            const terminal = document.getElementById('terminal');

            try {
                status.textContent = 'Starting terminal...';
                status.className = 'text-xs text-yellow-400';

                const response = await fetch('{{ url("test-terminal/start") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Failed to start terminal');
                }

                // Connect to ttyd
                const url = `http://localhost:${data.port}`;
                terminal.src = url;

                status.textContent = 'Connected';
                status.className = 'text-xs text-green-400';

                console.log('Terminal started on port:', data.port);

            } catch (error) {
                console.error('Failed to start terminal:', error);
                status.textContent = 'Connection failed';
                status.className = 'text-xs text-red-400';

                terminal.srcdoc = `
                    <html>
                    <head>
                        <style>
                            body {
                                background: black;
                                color: #ff6b6b;
                                font-family: monospace;
                                padding: 20px;
                            }
                        </style>
                    </head>
                    <body>
                        <h2>Connection Failed</h2>
                        <p>${error.message}</p>
                        <p>Please refresh the page to try again.</p>
                    </body>
                    </html>
                `;
            }
        }

        // Start when page loads
        startTerminal();
    </script>
</x-layouts.app>