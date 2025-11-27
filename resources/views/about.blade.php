<x-layouts.marketing title="About - FluxSSH">
    <!-- Background Clip Animation -->
    <div class="fixed inset-0 z-0 grid grid-cols-[repeat(auto-fit,minmax(100px,1fr))] opacity-20 pointer-events-none">
        @for ($i = 0; $i < 20; $i++)
            <div class="h-full border-r border-white/5 bg-gradient-to-b from-transparent via-white/5 to-transparent"
                 style="animation: fade-in-slide-up-blur 1s cubic-bezier(0.16, 1, 0.3, 1) both; animation-delay: {{ $i * 0.1 }}s;"></div>
        @endfor
    </div>

    <div class="relative z-10 max-w-4xl mx-auto py-12 md:py-20 px-4">
        <div class="text-center mb-16 space-y-4 animate-fade-in-slide-up-blur">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-text-primary">About FluxSSH</h1>
            <p class="text-xl text-text-secondary max-w-2xl mx-auto">
                We're building the future of server management.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24">
            <div class="space-y-6 animate-fade-in-slide-up-blur" style="animation-delay: 0.2s;">
                <h2 class="text-2xl font-semibold text-text-primary">Our Mission</h2>
                <p class="text-text-secondary leading-relaxed">
                    FluxSSH was born out of frustration with existing SSH clients. We wanted a tool that was not only powerful and secure but also a joy to use. 
                    Our mission is to provide developers and system administrators with a modern, efficient, and beautiful interface for managing their infrastructure.
                </p>
                <p class="text-text-secondary leading-relaxed">
                    We believe that the tools you use every day should be delightful. That's why we obsess over every pixel and interaction in FluxSSH.
                </p>
            </div>
            <div class="relative group animate-fade-in-slide-up-blur" style="animation-delay: 0.4s;">
                <div class="absolute -inset-1 bg-gradient-to-r from-primary-500 to-orange-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative rounded-xl bg-bg-surface border border-white/10 aspect-square flex items-center justify-center overflow-hidden">
                    <!-- Placeholder for Team Image -->
                    <div class="absolute inset-0 bg-gradient-to-br from-bg-surface-alt to-bg-surface"></div>
                    <span class="relative text-text-tertiary font-medium">Team Photo Placeholder</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-primary-500/50 transition-colors animate-fade-in-slide-up-blur" style="animation-delay: 0.5s;">
                <div class="w-12 h-12 rounded-lg bg-primary-500/20 flex items-center justify-center mb-4 text-primary-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-text-primary mb-2">Fast & Reliable</h3>
                <p class="text-text-secondary text-sm">Built on top of proven technologies to ensure your connections are always stable and fast.</p>
            </div>
            
            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-primary-500/50 transition-colors animate-fade-in-slide-up-blur" style="animation-delay: 0.6s;">
                <div class="w-12 h-12 rounded-lg bg-orange-500/20 flex items-center justify-center mb-4 text-orange-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-text-primary mb-2">Secure by Default</h3>
                <p class="text-text-secondary text-sm">Security is our top priority. Your keys and data are encrypted and never leave your device.</p>
            </div>
            
            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-primary-500/50 transition-colors animate-fade-in-slide-up-blur" style="animation-delay: 0.7s;">
                <div class="w-12 h-12 rounded-lg bg-amber-500/20 flex items-center justify-center mb-4 text-amber-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-text-primary mb-2">Beautiful UI</h3>
                <p class="text-text-secondary text-sm">A user interface that is easy on the eyes and intuitive to navigate, even for complex tasks.</p>
            </div>
        </div>
    </div>
</x-layouts.marketing>
