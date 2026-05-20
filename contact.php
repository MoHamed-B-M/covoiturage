<?php include "header.php"; ?>

<div class="flex flex-col items-center justify-center py-20 px-4">
    <div class="max-w-4xl w-full space-y-12">
        <div class="text-center space-y-4">
            <span class="text-mint font-black text-[10px] uppercase tracking-[0.3em]">Support Center</span>
            <h1 class="text-6xl font-black tracking-tighter text-white uppercase">Get in Touch</h1>
            <p class="text-slate-500 font-medium max-w-xl mx-auto">Notre équipe d'agents est prête à répondre à vos signaux de communication.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Email Card -->
            <div class="glass p-10 rounded-[48px] border border-white/5 space-y-6 hover:border-mint/30 transition-all group">
                <div class="w-16 h-16 bg-mint/10 rounded-2xl flex items-center justify-center text-mint shadow-glow-mint group-hover:scale-110 transition-transform">
                    <i class="ph ph-envelope-open text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-white tracking-tight mb-4 uppercase">Canaux E-mail</h3>
                    <div class="space-y-2">
                        <p class="text-slate-400 font-bold flex items-center gap-2">
                            <i class="ph ph-at text-mint"></i> benmohamedm715@gmail.com
                        </p>
                        <p class="text-slate-400 font-bold flex items-center gap-2">
                            <i class="ph ph-at text-mint"></i> khadraouiwajih3@gmail.com
                        </p>
                    </div>
                </div>
                <a href="mailto:benmohamedm715@gmail.com" class="inline-flex items-center gap-3 bg-mint text-midnight px-8 py-4 rounded-2xl font-black uppercase text-xs shadow-glow-mint hover:scale-[1.02] transition-all w-full justify-center">
                    Envoyer un E-mail <i class="ph ph-paper-plane-tilt text-xl"></i>
                </a>
            </div>

            <!-- Voice Card -->
            <div class="glass p-10 rounded-[48px] border border-white/5 space-y-6 hover:border-cyber/30 transition-all group">
                <div class="w-16 h-16 bg-cyber/10 rounded-2xl flex items-center justify-center text-cyber shadow-glow-purple group-hover:scale-110 transition-transform">
                    <i class="ph ph-phone-call text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-white tracking-tight mb-4 uppercase">Signal Vocal</h3>
                    <p class="text-slate-400 font-bold flex items-center gap-2 mb-1">
                        <i class="ph ph-phone text-cyber"></i> +216 53 07 09 09
                    </p>
                    <p class="text-slate-500 text-xs font-bold tracking-widest uppercase">Disponible 24/7 pour assistance</p>
                </div>
                <a href="tel:+21653070909" class="inline-flex items-center gap-3 bg-cyber-gradient text-white px-8 py-4 rounded-2xl font-black uppercase text-xs shadow-glow-purple hover:scale-[1.02] transition-all w-full justify-center">
                    Appeler Maintenant <i class="ph ph-phone-outgoing text-xl"></i>
                </a>
            </div>
        </div>

        <!-- Location Footer -->
        <div class="glass p-8 rounded-[32px] border border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-slate-400">
                    <i class="ph ph-map-pin text-2xl"></i>
                </div>
                <div>
                    <p class="text-white font-black uppercase tracking-widest text-sm">Station de Base</p>
                    <p class="text-slate-500 font-bold">Sidi Bouzid, Tunisie</p>
                </div>
            </div>
            <div class="h-px w-full md:w-px md:h-12 bg-white/5"></div>
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em]">Powered By</span>
                <span class="text-mint font-black tracking-tighter text-xl uppercase italic">ISET SIDI BOUZID</span>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
