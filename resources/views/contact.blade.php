<x-layouts.marketing title="Contact - FluxSSH">
    <!-- Background Clip Animation -->
    <div class="fixed inset-0 z-0 grid grid-cols-[repeat(auto-fit,minmax(100px,1fr))] opacity-20 pointer-events-none">
        @for ($i = 0; $i < 20; $i++)
            <div class="h-full border-r border-white/5 bg-gradient-to-b from-transparent via-white/5 to-transparent"
                 style="animation: fade-in-slide-up-blur 1s cubic-bezier(0.16, 1, 0.3, 1) both; animation-delay: {{ $i * 0.1 }}s;"></div>
        @endfor
    </div>

    <div class="relative z-10 max-w-2xl mx-auto py-12 md:py-20 px-4">
        <div class="text-center mb-12 space-y-4 animate-fade-in-slide-up-blur">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-text-primary">Get in Touch</h1>
            <p class="text-xl text-text-secondary">
                Have questions or feedback? We'd love to hear from you.
            </p>
        </div>

        <div class="bg-bg-surface border border-white/10 rounded-2xl p-8 backdrop-blur-sm animate-fade-in-slide-up-blur" style="animation-delay: 0.2s;">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium text-text-tertiary">Name</label>
                        <input type="text" id="name" class="w-full px-4 py-3 rounded-lg bg-bg-surface-alt border border-white/10 text-text-primary placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors outline-none" placeholder="John Doe">
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-text-tertiary">Email</label>
                        <input type="email" id="email" class="w-full px-4 py-3 rounded-lg bg-bg-surface-alt border border-white/10 text-text-primary placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors outline-none" placeholder="john@example.com">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="subject" class="text-sm font-medium text-text-tertiary">Subject</label>
                    <select id="subject" class="w-full px-4 py-3 rounded-lg bg-bg-surface-alt border border-white/10 text-text-primary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors outline-none appearance-none">
                        <option>General Inquiry</option>
                        <option>Support</option>
                        <option>Feedback</option>
                        <option>Partnership</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="message" class="text-sm font-medium text-text-tertiary">Message</label>
                    <textarea id="message" rows="5" class="w-full px-4 py-3 rounded-lg bg-bg-surface-alt border border-white/10 text-text-primary placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors outline-none resize-none" placeholder="How can we help you?"></textarea>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-primary-500 to-orange-600 rounded-xl blur opacity-50 group-hover:opacity-75 transition duration-200"></div>
                    <button type="submit" class="relative w-full py-4 rounded-xl bg-primary-500 hover:bg-primary-600 text-white font-semibold transition-colors shadow-lg shadow-primary-500/20 overflow-hidden">
                        <span class="absolute inset-0 rounded-xl border border-white/10"></span>
                        <span class="absolute inset-0 rounded-xl border border-transparent" 
                              style="mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask-composite: exclude; background: conic-gradient(from 0deg, transparent 0deg, var(--color-primary-500) 90deg, transparent 180deg); animation: border-beam 4s linear infinite;"></span>
                        <span class="relative z-10">Send Message</span>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-12 text-center animate-fade-in-slide-up-blur" style="animation-delay: 0.4s;">
            <p class="text-text-tertiary">
                Prefer email? Reach us at <a href="mailto:support@fluxssh.com" class="text-primary-500 hover:text-primary-600 transition-colors">support@fluxssh.com</a>
            </p>
        </div>
    </div>
</x-layouts.marketing>
