<x-layouts.marketing title="FluxSSH - The Next Gen SSH Client">
    <div class="relative min-h-[80vh] flex flex-col items-center justify-center text-center">
        <!-- Background Glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto space-y-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-sm text-indigo-300 mb-4 animate-fade-in-up">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                v2.0 is now available
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-white via-white to-white/50 pb-2">
                Manage Servers <br />
                <span class="text-white">Like a Pro.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                FluxSSH is the modern, secure, and beautiful way to manage your SSH connections. 
                Built for developers who care about their tools.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <div class="beam-container rounded-xl p-[1px]">
                    <div class="beam-border"></div>
                    <a href="{{ route('register') }}" class="beam-btn block px-8 py-4 rounded-xl bg-zinc-900 text-white font-semibold text-lg tracking-wide shadow-2xl shadow-indigo-500/20">
                        Get Started Free
                    </a>
                </div>
                
                <a href="#" class="px-8 py-4 rounded-xl text-zinc-400 hover:text-white font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Watch Demo
                </a>
            </div>
        </div>

        <!-- UI Mockup -->
        <div class="mt-24 w-full max-w-5xl mx-auto relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
            <div class="relative rounded-xl bg-zinc-900 border border-white/10 shadow-2xl overflow-hidden aspect-video flex items-center justify-center">
                <div class="text-zinc-500 flex flex-col items-center gap-4">
                    <svg class="w-16 h-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium">Application Screenshot Placeholder</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.marketing>
