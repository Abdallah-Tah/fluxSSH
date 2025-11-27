<x-layouts.marketing title="FluxSSH - Mobile Server Management">
    <!-- Background Clip Animation -->
    <div class="fixed inset-0 z-0 grid grid-cols-[repeat(auto-fit,minmax(100px,1fr))] opacity-20 pointer-events-none">
        @for ($i = 0; $i < 20; $i++)
            <div class="h-full border-r border-white/5 bg-gradient-to-b from-transparent via-white/5 to-transparent"
                 style="animation: fade-in-slide-up-blur 1s cubic-bezier(0.16, 1, 0.3, 1) both; animation-delay: {{ $i * 0.1 }}s;"></div>
        @endfor
    </div>

    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen text-center px-4 overflow-hidden">
        
        <!-- Hero Section -->
        <div class="max-w-5xl mx-auto space-y-12 pt-20 pb-20">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-sm text-primary-500 backdrop-blur-md animate-fade-in-slide-up-blur" style="animation-delay: 0.2s;">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-500 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-600"></span>
                </span>
                <span class="font-medium">v2.0 is now available</span>
            </div>
            
            <!-- Text Reveal Animation -->
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold tracking-tight text-text-primary leading-tight">
                <div class="overflow-hidden">
                    <span class="inline-block animate-text-reveal" style="animation-delay: 0.3s;">Your Servers.</span>
                </div>
                <div class="overflow-hidden">
                    <span class="inline-block bg-gradient-to-r from-primary-500 via-orange-400 to-primary-500 bg-clip-text text-transparent animate-text-reveal" style="animation-delay: 0.5s;">Anywhere.</span>
                </div>
            </h1>
            
            <p class="text-xl md:text-2xl text-text-secondary max-w-2xl mx-auto leading-relaxed animate-fade-in-slide-up-blur" style="animation-delay: 0.7s;">
                A full Linux terminal experience — optimized for mobile.
            </p>
            
            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 animate-fade-in-slide-up-blur" style="animation-delay: 0.9s;">
                <!-- Border Beam Button -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-primary-500 to-orange-600 rounded-full blur opacity-50 group-hover:opacity-75 transition duration-200"></div>
                    <a href="{{ route('register') }}" class="relative flex items-center justify-center px-8 py-4 bg-bg-surface rounded-full leading-none text-text-primary font-semibold tracking-wide overflow-hidden">
                        <span class="absolute inset-0 rounded-full border border-white/10"></span>
                        <span class="absolute inset-0 rounded-full border border-transparent" 
                              style="mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask-composite: exclude; background: conic-gradient(from 0deg, transparent 0deg, var(--color-primary-500) 90deg, transparent 180deg); animation: border-beam 4s linear infinite;"></span>
                        <span class="relative z-10">Get Started Free</span>
                    </a>
                </div>
                
                <a href="#" class="px-8 py-4 rounded-full text-text-secondary hover:text-text-primary font-medium transition-colors flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </div>
                    Use on Mobile
                </a>
            </div>
        </div>

        <!-- Mobile-Focused Benefits Row -->
        <div class="w-full max-w-7xl mx-auto mb-32 grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
            <div class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/50 transition-colors text-left animate-fade-in-slide-up-blur" style="animation-delay: 1.0s;">
                <div class="w-12 h-12 rounded-xl bg-primary-500/10 flex items-center justify-center mb-6 text-primary-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-text-primary mb-2">Mobile-Optimized Terminal</h3>
                <p class="text-text-secondary">Full Linux shell, gestures, tab completion, and real-time output.</p>
            </div>
            <div class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/50 transition-colors text-left animate-fade-in-slide-up-blur" style="animation-delay: 1.1s;">
                <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-6 text-orange-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-text-primary mb-2">Instant Commands</h3>
                <p class="text-text-secondary">Run deployments, restart services, tail logs — all from your phone.</p>
            </div>
            <div class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/50 transition-colors text-left animate-fade-in-slide-up-blur" style="animation-delay: 1.2s;">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-6 text-emerald-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-text-primary mb-2">Secure by Design</h3>
                <p class="text-text-secondary">Key-based authentication, isolated sessions, no credentials stored.</p>
            </div>
        </div>

        <!-- What You Can Do Section -->
        <div class="w-full max-w-4xl mx-auto mb-32 px-4">
            <h2 class="text-3xl font-bold text-text-primary mb-12">Power in Your Pocket</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left">
                @foreach(['SSH into any server instantly', 'Navigate directories with one tap', 'Restart Nginx / PHP / Node services', 'View logs in real-time', 'Edit files with an integrated editor', 'Manage multiple servers from one dashboard', 'Deploy updates without opening a laptop'] as $item)
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-bg-surface-alt/50 border border-border-subtle">
                        <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-text-secondary font-medium">{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Visual Mockup Section -->
        <div class="w-full max-w-6xl mx-auto mb-32 px-4">
            <div class="relative rounded-3xl bg-bg-surface border border-border-subtle p-8 md:p-12 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">

                    <!-- iPhone 17 Mockup -->
                    <div class="relative mx-auto md:mx-0 w-[320px] h-[650px] bg-black rounded-[55px] shadow-2xl ring-8 ring-zinc-800 overflow-hidden">
                        
                        <!-- Dynamic Island -->
                        <div class="absolute top-3 left-1/2 -translate-x-1/2 w-[120px] h-[35px] bg-black rounded-full z-30 flex items-center justify-center gap-3 px-3">
                            <div class="w-2 h-2 rounded-full bg-[#1a1a1a]"></div> <!-- Camera -->
                            <div class="w-1.5 h-1.5 rounded-full bg-[#0f0f0f]"></div> <!-- Sensor -->
                        </div>

                        <!-- Status Bar -->
                        <div class="absolute top-0 inset-x-0 h-12 z-20 flex justify-between px-7 items-center pt-2">
                            <div class="text-[15px] text-white font-semibold tracking-wide">9:41</div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                                <div class="w-6 h-3 rounded-[4px] border border-white/30 relative ml-1">
                                    <div class="absolute inset-0.5 bg-white rounded-[2px]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Screen Content -->
                        <div class="absolute inset-0 pt-14 pb-1 bg-bg-app flex flex-col font-mono text-xs leading-relaxed text-text-primary">

                            <!-- Terminal Header -->
                            <div class="px-4 py-2 border-b border-white/5 flex items-center justify-between bg-bg-surface/50 backdrop-blur-md">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                </div>
                                <span class="text-[10px] text-text-tertiary uppercase tracking-wider">production-01</span>
                                <svg class="w-4 h-4 text-text-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            </div>

                            <!-- Terminal Body -->
                            <div class="flex-1 p-4 space-y-4 overflow-hidden relative">
                                <!-- Scanlines effect -->
                                <div class="absolute inset-0 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.1)_50%),linear-gradient(90deg,rgba(255,0,0,0.03),rgba(0,255,0,0.01),rgba(0,0,255,0.03))] z-0 pointer-events-none bg-[length:100%_2px,3px_100%]"></div>

                                <div class="relative z-10 space-y-3">
                                    <div class="flex flex-wrap">
                                        <span class="text-primary-500 font-bold">root@flux:~#&nbsp;</span>
                                        <span class="text-text-primary">flux connect --server=prod-db</span>
                                    </div>

                                    <div class="text-text-tertiary text-[11px]">
                                        Connecting to production-db (192.168.1.45)...<br>
                                        <span class="text-emerald-500">✓ Connection established (23ms)</span>
                                    </div>

                                    <div class="flex flex-wrap">
                                        <span class="text-primary-500 font-bold">root@prod-db:~#&nbsp;</span>
                                        <span class="text-text-primary">htop</span>
                                    </div>

                                    <!-- Fake htop output -->
                                    <div class="bg-bg-surface/50 p-2 rounded border border-white/5 text-[10px] font-mono leading-tight text-text-secondary">
                                        <div class="flex justify-between"><span>CPU [||||||||||||| 85%]</span> <span class="text-primary-500">Tasks: 42</span></div>
                                        <div class="flex justify-between"><span>MEM [|||||||...... 40%]</span> <span class="text-orange-500">Uptime: 14d</span></div>
                                        <div class="mt-2 text-text-tertiary">
                                            PID USER PRI NI VIRT RES SHR S CPU% MEM% TIME+ Command<br>
                                            892 root  20  0 145M 24M 12M S  2.4  1.2  0:45 mysql<br>
                                            412 www   20  0 402M 45M 22M S  1.1  2.4  1:12 php-fpm
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center">
                                        <span class="text-primary-500 font-bold">root@prod-db:~#&nbsp;</span>
                                        <span class="animate-pulse bg-primary-500 w-2 h-4 block"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Action Button (Mobile Touch) -->
                            <div class="absolute bottom-20 right-4 z-20">
                                <div class="w-12 h-12 rounded-full bg-primary-500 shadow-lg shadow-primary-500/30 flex items-center justify-center text-white">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                </div>
                            </div>

                            <!-- Bottom Tab Bar (iOS Style) -->
                            <div class="h-[88px] bg-bg-surface/90 backdrop-blur-xl border-t border-white/5 flex items-start justify-around pt-4 pb-8 text-text-tertiary relative z-20">
                                <div class="flex flex-col items-center gap-1 text-primary-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px] font-medium">Terminal</span>
                                </div>
                                <div class="flex flex-col items-center gap-1 hover:text-text-primary transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                    <span class="text-[10px] font-medium">Snippets</span>
                                </div>
                                <div class="flex flex-col items-center gap-1 hover:text-text-primary transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-[10px] font-medium">Settings</span>
                                </div>
                            </div>

                            <!-- Home Indicator -->
                            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 w-32 h-1 bg-white/20 rounded-full z-30"></div>

                        </div>
                    </div>

                    <!-- Text Section -->
                    <div class="text-left space-y-6">
                        <h2 class="text-4xl font-bold text-text-primary leading-tight">
                            A real terminal.<br>
                            On Android, iOS, and Web.
                        </h2>

                        <p class="text-lg text-text-secondary">
                            Manage your servers from anywhere. FluxSSH gives you a fast, secure,
                            and touch-optimized terminal that works beautifully on every device —
                            right from your browser or your phone.
                        </p>

                        <ul class="space-y-3 text-text-secondary">
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-primary-500/20 flex items-center justify-center text-primary-500 text-xs font-bold">✓</div>
                                Mobile-optimized Linux terminal
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-primary-500/20 flex items-center justify-center text-primary-500 text-xs font-bold">✓</div>
                                Intelligent command history & completion
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-primary-500/20 flex items-center justify-center text-primary-500 text-xs font-bold">✓</div>
                                Multi-session & real-time streaming output
                            </li>
                        </ul>
                    </div>

                </div>
                
                <!-- Background Glow -->
                <div class="absolute top-1/2 right-0 -translate-y-1/2 w-[420px] h-[420px] bg-primary-500/20 blur-[120px] rounded-full pointer-events-none"></div>
            </div>
        </div>

        <!-- Why Mobile Matters Section -->
        <div class="w-full max-w-4xl mx-auto mb-32 px-4">
            <div class="text-center space-y-6 mb-12">
                <h2 class="text-3xl font-bold text-text-primary">Why Mobile Matters</h2>
                <blockquote class="text-xl text-text-secondary italic font-medium">
                    "You don’t always have your laptop. But your servers always need you."
                </blockquote>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach(['On the road', 'On-call at night', 'During travel', 'Quick sanity checks', 'Emergencies'] as $situation)
                    <div class="p-4 rounded-xl bg-bg-surface border border-border-subtle text-center">
                        <span class="text-sm font-medium text-text-secondary">{{ $situation }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Designed for Developers -->
        <div class="w-full max-w-3xl mx-auto mb-32 px-4 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-500/10 text-primary-500 text-xs font-bold uppercase tracking-widest mb-6">
                ✨ Built for Developers
            </div>
            <h2 class="text-3xl font-bold text-text-primary mb-6">No bloat. No noise.</h2>
            <p class="text-lg text-text-secondary leading-relaxed">
                Just a clean, fast terminal with all the power of Linux — accessible from any device.
                We stripped away the complexity to focus on what matters: your code and your infrastructure.
            </p>
        </div>

        <!-- Social Proof / Roadmap -->
        <div class="w-full max-w-4xl mx-auto mb-32 px-4">
            <div class="p-8 rounded-2xl bg-bg-surface-alt/30 border border-border-subtle text-center">
                <h3 class="text-lg font-semibold text-text-primary mb-6">v2.0 available — now with:</h3>
                <div class="flex flex-wrap justify-center gap-4 md:gap-8">
                    @foreach(['Interactive sessions', 'Multi-server dashboard', 'Real-time output streaming', 'Mobile-optimized UI'] as $feature)
                        <div class="flex items-center gap-2 text-text-secondary">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                            {{ $feature }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Why Choose FluxSSH Panel -->
        <div class="w-full max-w-5xl mx-auto mb-32 px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-3xl font-bold text-text-primary mb-6">Why Choose FluxSSH?</h2>
                    <p class="text-text-secondary mb-8">
                        We're not just another SSH app. We're rethinking how developers interact with their infrastructure on the go.
                    </p>
                    <a href="{{ route('about') }}" class="text-primary-500 font-medium hover:text-primary-600 transition-colors flex items-center gap-2">
                        Learn more about our mission
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach(['Persistent command history', 'Smart directory tracking', 'Auto-completion', 'Output logging', 'Team access (future)', 'Beautiful UI', 'Works from browser or mobile'] as $item)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-bg-surface border border-border-subtle">
                            <span class="text-text-primary font-medium">{{ $item }}</span>
                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Final CTA -->
        <div class="w-full max-w-4xl mx-auto mb-32 px-4 text-center">
            <h2 class="text-4xl font-bold text-text-primary mb-8">Ready to take control?</h2>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-full bg-primary-500 hover:bg-primary-600 text-white font-semibold transition-colors shadow-lg shadow-primary-500/20">
                    Open Terminal on Your Phone
                </a>
            </div>
        </div>

    </div>
</x-layouts.marketing>
