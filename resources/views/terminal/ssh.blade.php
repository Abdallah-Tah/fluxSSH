<x-layouts.app.sidebar :title="'SSH Terminal - ' . $server->name">
    <flux:main class="p-0 h-[calc(100vh-4rem)]">
        <div class="h-full p-4">
            <div
                class="h-full flex flex-col bg-slate-900 rounded-lg border border-slate-700 shadow-2xl overflow-hidden relative">
                <livewire:terminal.interactive-terminal :server="$server" />
            </div>
        </div>
    </flux:main>
</x-layouts.app.sidebar>
