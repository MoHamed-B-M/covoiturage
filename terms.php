<?php include "header.php"; ?>

<div class="py-20 px-4 max-w-4xl mx-auto space-y-16">
    <div class="text-center space-y-4">
        <span class="text-mint font-black text-[10px] uppercase tracking-[0.3em]">Operational Rules</span>
        <h1 class="text-5xl font-black tracking-tighter text-white uppercase">Terms of Service</h1>
        <div class="w-20 h-1 bg-mint mx-auto rounded-full"></div>
    </div>

    <div class="space-y-12">
        <!-- Introduction -->
        <section class="space-y-6">
            <h3 class="text-2xl font-black text-white uppercase tracking-tight flex items-center gap-3">
                <i class="ph ph-terminal-window text-mint"></i> 01. Protocole de Simulation
            </h3>
            <p class="text-slate-400 font-medium leading-relaxed">
                En accédant à la plateforme <span class="text-white">Cyber Covoiturage</span>, vous reconnaissez que ce système est une simulation éducative. Toutes les interactions, réservations et publications de trajets sont effectuées dans un environnement contrôlé à des fins de démonstration technique.
            </p>
        </section>

        <!-- Rules -->
        <section class="space-y-6">
            <h3 class="text-2xl font-black text-white uppercase tracking-tight flex items-center gap-3">
                <i class="ph ph-shield-check text-mint"></i> 02. Code de Conduite des Agents
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass p-6 rounded-3xl border border-white/5 space-y-3">
                    <h4 class="text-mint font-black uppercase text-xs tracking-widest">Exactitude des Missions</h4>
                    <p class="text-slate-500 text-sm">Les agents s'engagent à fournir des détails de mission (trajets) réalistes et précis au sein de la simulation.</p>
                </div>
                <div class="glass p-6 rounded-3xl border border-white/5 space-y-3">
                    <h4 class="text-mint font-black uppercase text-xs tracking-widest">Respect de l'Infrastructure</h4>
                    <p class="text-slate-500 text-sm">Toute tentative de compromission de l'intégrité du système entraînera une suspension immédiate de l'accès.</p>
                </div>
            </div>
        </section>

        <!-- Liability -->
        <section class="space-y-6">
            <h3 class="text-2xl font-black text-white uppercase tracking-tight flex items-center gap-3">
                <i class="ph ph-scales text-mint"></i> 03. Limitation de Responsabilité
            </h3>
            <p class="text-slate-400 font-medium leading-relaxed">
                Rydo Industries et l'ISET Sidi Bouzid ne peuvent être tenus responsables des actions entreprises en dehors de l'environnement de simulation. Ce projet ne facilite pas de véritables transactions financières ou des services de transport réels.
            </p>
        </section>

        <!-- Updates -->
        <div class="p-8 rounded-[32px] bg-white/5 border border-white/10 flex items-center justify-between gap-6 italic">
            <p class="text-slate-500 text-sm font-medium">Les protocoles de service peuvent être mis à jour sans préavis pour refléter l'évolution des objectifs académiques.</p>
            <i class="ph ph-info text-2xl text-slate-600"></i>
        </div>
    </div>

    <div class="text-center pt-10">
        <a href="index.php" class="text-xs font-black uppercase tracking-widest text-mint hover:underline">
            Retourner au Terminal Principal
        </a>
    </div>
</div>

<?php include "footer.php"; ?>
