<?php
include "header.php";
include "db.php";

$trips = $pdo
    ->query(
        "SELECT T.*, U.id as user_id, U.name, U.phone, U.profile_pic FROM Trips T JOIN Users U ON T.user_id = U.id WHERE T.seats > 0 ORDER BY T.date_trip ASC LIMIT 6",
    )
    ->fetchAll();
?>

<!-- Hero Section -->
<div class="relative min-h-[70vh] flex flex-col items-center justify-center pt-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center w-full">
        
        <!-- Left Content -->
        <div class="space-y-8 text-center lg:text-left z-10">
            <div class="inline-block px-4 py-1.5 rounded-full bg-mint/10 border border-mint/20 text-mint text-[10px] font-black uppercase tracking-[0.2em] animate-pulse">
                Next-Gen Carpooling
            </div>
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter leading-[0.9]">
                DRIVE THE <br>
                <span class="text-gradient-mint italic">FUTURE.</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl font-medium max-w-lg mx-auto lg:mx-0 leading-relaxed opacity-80">
                Connect with reliable drivers in Sidi Bouzid. Secure, sustainable, and purely cybernetic travel experiences.
            </p>
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                <a href="#explore" class="bg-cyber-gradient text-white px-8 py-4 rounded-2xl font-black text-sm uppercase shadow-glow-purple hover:scale-105 transition-transform">Get Started</a>
                <a href="search.php" class="px-8 py-4 rounded-2xl border border-slate-700 font-black text-sm uppercase hover:bg-slate-800 transition-colors">How it works</a>
            </div>
        </div>

        <!-- Right Visual: Isometric City Map Stylized -->
        <div class="relative hidden lg:block">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-cyber/20 rounded-full filter blur-[100px]"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-mint/10 rounded-full filter blur-[100px]"></div>
            
            <svg viewBox="0 0 800 600" class="w-full h-auto drop-shadow-2xl">
                <!-- Isometric Grid Lines -->
                <path d="M100 300 L400 150 L700 300 L400 450 Z" fill="none" stroke="#2DD4BF" stroke-width="0.5" opacity="0.2" />
                <path d="M100 310 L400 160 L700 310 L400 460 Z" fill="none" stroke="#A855F7" stroke-width="0.5" opacity="0.1" />
                
                <!-- Glowing Route Lines -->
                <path d="M200 350 Q 400 200, 600 350" fill="none" stroke="url(#mintGradient)" stroke-width="3" class="route-line shadow-glow-mint" />
                <path d="M300 250 Q 400 400, 500 200" fill="none" stroke="url(#purpleGradient)" stroke-width="3" class="route-line shadow-glow-purple" />
                
                <!-- Connection Nodes -->
                <circle cx="200" cy="350" r="6" fill="#2DD4BF" class="animate-ping" />
                <circle cx="200" cy="350" r="4" fill="#2DD4BF" />
                
                <circle cx="600" cy="350" r="6" fill="#A855F7" class="animate-ping" />
                <circle cx="600" cy="350" r="4" fill="#A855F7" />

                <!-- Gradients for SVG -->
                <defs>
                    <linearGradient id="mintGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#2DD4BF" />
                        <stop offset="100%" stop-color="transparent" />
                    </linearGradient>
                    <linearGradient id="purpleGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#A855F7" />
                        <stop offset="100%" stop-color="transparent" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Floating Glass Search Bar -->
    <div class="w-full max-w-4xl mt-16 z-20">
        <form action="search.php" method="GET" class="glass p-3 rounded-[32px] flex flex-col md:flex-row items-center gap-2 border border-white/10 shadow-2xl backdrop-blur-3xl">
            <div class="flex-1 flex items-center px-8 gap-4 border-r border-white/5 last:border-0 w-full md:w-auto">
                <i class="bi bi-geo-alt-fill text-mint text-xl"></i>
                <div class="flex flex-col text-left">
                    <label class="text-[9px] uppercase font-black text-slate-500 tracking-[0.2em]">Origin</label>
                    <input type="text" name="departure" placeholder="Where from?" class="bg-transparent border-0 p-0 text-sm font-bold text-white focus:ring-0 placeholder-slate-600 w-full" required>
                </div>
            </div>
            <div class="flex-1 flex items-center px-8 gap-4 border-r border-white/5 last:border-0 w-full md:w-auto">
                <i class="bi bi-cursor-fill text-cyber text-xl"></i>
                <div class="flex flex-col text-left">
                    <label class="text-[9px] uppercase font-black text-slate-500 tracking-[0.2em]">Destiny</label>
                    <input type="text" name="destination" placeholder="Where to?" class="bg-transparent border-0 p-0 text-sm font-bold text-white focus:ring-0 placeholder-slate-600 w-full" required>
                </div>
            </div>
            <div class="flex-1 flex items-center px-8 gap-4 border-r border-white/5 last:border-0 w-full md:w-auto">
                <i class="bi bi-calendar2-week-fill text-mint text-xl"></i>
                <div class="flex flex-col text-left">
                    <label class="text-[9px] uppercase font-black text-slate-500 tracking-[0.2em]">Launch</label>
                    <input type="date" name="date" value="<?= date('Y-m-d') ?>" class="bg-transparent border-0 p-0 text-sm font-bold text-white focus:ring-0 w-full [color-scheme:dark]">
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto bg-mint text-midnight px-10 py-5 rounded-[24px] font-black text-xs uppercase shadow-glow-mint hover:scale-[1.02] active:scale-95 transition-all">
                Execute
            </button>
        </form>
    </div>
</div>

<!-- Active Missions (Trips) Section -->
<div class="mt-32" id="explore">
    <div class="flex items-end justify-between mb-16 px-4">
        <div>
            <span class="text-mint font-black text-[10px] uppercase tracking-[0.3em]">Live Feed</span>
            <h2 class="text-5xl font-black tracking-tighter mt-2 text-white">ACTIVE MISSIONS</h2>
        </div>
        <a href="search.php" class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-mint flex items-center gap-3 group transition-colors">
            Access All <i class="bi bi-arrow-right-short text-xl transition-transform group-hover:translate-x-2"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <?php foreach ($trips as $t): ?>
        <div class="glass-card rounded-[32px] p-8 group relative overflow-hidden">
            <!-- Background Accent -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-mint/5 rounded-full group-hover:bg-mint/10 transition-colors"></div>
            
            <div class="flex justify-between items-start mb-8">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img src="<?= $t["profile_pic"] ?: 'https://ui-avatars.com/api/?name='.urlencode($t['name']).'&background=0F172A&color=2DD4BF' ?>" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-white/10 group-hover:ring-mint/30 transition-all shadow-xl">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-midnight"></div>
                    </div>
                    <div>
                        <h4 class="font-black text-white text-lg tracking-tight"><?= htmlspecialchars($t["name"]) ?></h4>
                        <span class="text-[9px] font-black text-mint uppercase tracking-[0.2em]">Verified Agent</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-tighter">Budget</span>
                    <span class="text-2xl font-black text-white"><?= $t["price"] ?> <small class="text-xs text-mint uppercase">TND</small></span>
                </div>
            </div>

            <div class="space-y-6 relative mb-8">
                <div class="flex gap-4">
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-mint shadow-glow-mint"></div>
                        <div class="w-0.5 h-10 bg-gradient-to-b from-mint/20 to-cyber/20"></div>
                        <div class="w-2 h-2 rounded-full bg-cyber shadow-glow-purple"></div>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block mb-1">Departure</span>
                            <p class="font-bold text-white text-sm"><?= htmlspecialchars($t["departure"]) ?></p>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block mb-1">Arrival</span>
                            <p class="font-bold text-white text-sm"><?= htmlspecialchars($t["destination"]) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 rounded-lg bg-white/5 text-[10px] font-bold text-slate-400 border border-white/5">
                        <i class="bi bi-clock-history mr-2"></i><?= date("H:i", strtotime($t["date_trip"])) ?>
                    </div>
                    <div class="px-3 py-1 rounded-lg bg-white/5 text-[10px] font-bold text-slate-400 border border-white/5">
                        <i class="bi bi-people-fill mr-2"></i><?= $t["seats"] ?> slots
                    </div>
                </div>
                <a href="book.php?id=<?= $t["id"] ?>" class="bg-white/5 hover:bg-mint hover:text-midnight text-white w-10 h-10 rounded-xl flex items-center justify-center transition-all border border-white/10 hover:border-mint shadow-xl">
                    <i class="bi bi-arrow-up-right-bold font-black"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include "footer.php"; ?>
