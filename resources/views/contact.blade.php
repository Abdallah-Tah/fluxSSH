<x-layouts.marketing title="Contact - FluxSSH">
    <div class="max-w-2xl mx-auto py-12 md:py-20">
        <div class="text-center mb-12 space-y-4">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-white">Get in Touch</h1>
            <p class="text-xl text-zinc-400">
                Have questions or feedback? We'd love to hear from you.
            </p>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-sm">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium text-zinc-300">Name</label>
                        <input type="text" id="name" class="w-full px-4 py-3 rounded-lg bg-black/20 border border-white/10 text-white placeholder-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors outline-none" placeholder="John Doe">
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-zinc-300">Email</label>
                        <input type="email" id="email" class="w-full px-4 py-3 rounded-lg bg-black/20 border border-white/10 text-white placeholder-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors outline-none" placeholder="john@example.com">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="subject" class="text-sm font-medium text-zinc-300">Subject</label>
                    <select id="subject" class="w-full px-4 py-3 rounded-lg bg-black/20 border border-white/10 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors outline-none appearance-none">
                        <option>General Inquiry</option>
                        <option>Support</option>
                        <option>Feedback</option>
                        <option>Partnership</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="message" class="text-sm font-medium text-zinc-300">Message</label>
                    <textarea id="message" rows="5" class="w-full px-4 py-3 rounded-lg bg-black/20 border border-white/10 text-white placeholder-zinc-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors outline-none resize-none" placeholder="How can we help you?"></textarea>
                </div>

                <button type="submit" class="w-full py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold transition-colors shadow-lg shadow-indigo-500/20">
                    Send Message
                </button>
            </form>
        </div>
        
        <div class="mt-12 text-center">
            <p class="text-zinc-500">
                Prefer email? Reach us at <a href="mailto:support@fluxssh.com" class="text-indigo-400 hover:text-indigo-300 transition-colors">support@fluxssh.com</a>
            </p>
        </div>
    </div>
</x-layouts.marketing>
