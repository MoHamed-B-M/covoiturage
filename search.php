<?php 
include "header.php";
include "db.php";

// Initialize search parameters
$departure = $_GET['departure'] ?? 'Sidi Bouzid';
$destination = $_GET['destination'] ?? '';
$date = $_GET['date'] ?? '';
$filter = $_GET['filter'] ?? '';

// Base Query
$sql = "SELECT T.*, U.id as user_id, U.name, U.phone, U.profile_pic 
        FROM Trips T 
        JOIN Users U ON T.user_id = U.id 
        WHERE T.seats > 0";
$params = [];

// Apply Search Filters
if (!empty($departure)) {
    $sql .= " AND T.departure LIKE ?";
    $params[] = "%$departure%";
}
if (!empty($destination)) {
    $sql .= " AND T.destination LIKE ?";
    $params[] = "%$destination%";
}
if (!empty($date)) {
    $sql .= " AND DATE(T.date_trip) = ?";
    $params[] = $date;
}

// Apply UI Chips Filters
if ($filter === 'today') {
    $sql .= " AND DATE(T.date_trip) = CURDATE()";
} elseif ($filter === 'seats') {
    $sql .= " AND T.seats >= 2";
}

// Sorting
if ($filter === 'price') {
    $sql .= " ORDER BY T.price ASC";
} else {
    $sql .= " ORDER BY T.date_trip ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$trips = $stmt->fetchAll();
?>

<!-- Immersive Animated Background -->
<div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
    <div class="mesh-gradient h-full w-full opacity-40"></div>
</div>

<div class="relative min-h-screen pt-12">
    <!-- Centered Search Hub -->
    <div class="max-w-5xl mx-auto px-4 mb-20 text-center space-y-12">
        <div class="space-y-4 animate-fade-in">
            <span class="text-mint font-black text-[10px] uppercase tracking-[0.4em]">Exploration Mode</span>
            <h1 class="text-6xl md:text-7xl font-black tracking-tighter text-white uppercase leading-[0.9]">Trouvez votre <br><span class="text-gradient-mint italic">prochaine mission</span></h1>
        </div>

        <div class="relative animate-scale-in">
            <form action="search.php" method="GET" class="glass p-2 md:p-3 rounded-full flex flex-col md:flex-row items-center gap-2 border border-white/10 shadow-2xl backdrop-blur-3xl group/search">
                <!-- Departure -->
                <div class="flex-1 w-full relative group">
                    <i class="ph ph-map-pin absolute left-6 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="text" name="departure" value="<?= htmlspecialchars($departure) ?>" placeholder="Départ" 
                        class="w-full bg-transparent h-14 md:h-16 pl-14 pr-6 text-sm font-bold text-white placeholder:text-slate-500 focus:outline-none transition-all">
                </div>
                
                <div class="hidden md:block w-px h-8 bg-white/10"></div>

                <!-- Destination -->
                <div class="flex-1 w-full relative group">
                    <i class="ph ph-navigation-arrow absolute left-6 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="text" name="destination" value="<?= htmlspecialchars($destination) ?>" placeholder="Destination" 
                        class="w-full bg-transparent h-14 md:h-16 pl-14 pr-6 text-sm font-bold text-white placeholder:text-slate-500 focus:outline-none transition-all">
                </div>

                <div class="hidden md:block w-px h-8 bg-white/10"></div>

                <!-- Date -->
                <div class="flex-1 w-full relative group">
                    <i class="ph ph-calendar-blank absolute left-6 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" 
                        class="w-full bg-transparent h-14 md:h-16 pl-14 pr-6 text-sm font-bold text-white [color-scheme:dark] focus:outline-none transition-all">
                </div>

                <button type="submit" class="w-full md:w-auto bg-mint text-midnight h-14 md:h-16 px-10 rounded-full font-black text-xs uppercase shadow-glow-mint hover:scale-[1.05] active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span>Rechercher</span>
                    <i class="ph ph-magnifying-glass text-lg"></i>
                </button>
            </form>

            <!-- Material 3 Filter Chips -->
            <div class="flex flex-wrap items-center justify-center gap-3 mt-8 animate-fade-up">
                <a href="search.php?departure=<?= urlencode($departure) ?>&destination=<?= urlencode($destination) ?>&date=<?= urlencode($date) ?>&filter=today" 
                   class="px-5 py-2.5 rounded-full border transition-all text-[10px] font-black uppercase tracking-widest flex items-center gap-2 <?= $filter === 'today' ? 'bg-mint text-midnight border-mint shadow-glow-mint' : 'bg-white/5 border-white/10 text-slate-400 hover:bg-white/10' ?>">
                    <i class="ph ph-clock"></i> Aujourd'hui
                </a>
                <a href="search.php?departure=<?= urlencode($departure) ?>&destination=<?= urlencode($destination) ?>&date=<?= urlencode($date) ?>&filter=price" 
                   class="px-5 py-2.5 rounded-full border transition-all text-[10px] font-black uppercase tracking-widest flex items-center gap-2 <?= $filter === 'price' ? 'bg-cyber text-white border-cyber shadow-glow-purple' : 'bg-white/5 border-white/10 text-slate-400 hover:bg-white/10' ?>">
                    <i class="ph ph-trend-up"></i> Prix: Bas-Haut
                </a>
                <a href="search.php?departure=<?= urlencode($departure) ?>&destination=<?= urlencode($destination) ?>&date=<?= urlencode($date) ?>&filter=seats" 
                   class="px-5 py-2.5 rounded-full border transition-all text-[10px] font-black uppercase tracking-widest flex items-center gap-2 <?= $filter === 'seats' ? 'bg-blue-500 text-white border-blue-500 shadow-[0_0_20px_rgba(59,130,246,0.3)]' : 'bg-white/5 border-white/10 text-slate-400 hover:bg-white/10' ?>">
                    <i class="ph ph-users"></i> 2+ Places
                </a>
                <?php if ($filter): ?>
                <a href="search.php?departure=<?= urlencode($departure) ?>&destination=<?= urlencode($destination) ?>&date=<?= urlencode($date) ?>" class="text-xs font-bold text-rose-500 hover:text-rose-400 ml-2">Effacer</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Dynamic Results Grid -->
    <div class="max-w-7xl mx-auto px-4 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($trips as $index => $t): ?>
            <div class="glass-card rounded-[32px] p-8 group relative overflow-hidden animate-fade-up" style="animation-delay: <?= $index * 100 ?>ms;">
                <div class="flex justify-between items-start mb-8">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <img src="<?= $t["profile_pic"] ?: 'https://ui-avatars.com/api/?name='.urlencode($t['name']).'&background=0F172A&color=2DD4BF' ?>" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-white/10 group-hover:ring-mint/30 transition-all shadow-xl">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-mint rounded-full border-2 border-midnight flex items-center justify-center text-[10px] text-midnight">
                                <i class="ph ph-seal-check-fill"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-black text-white text-lg tracking-tight"><?= htmlspecialchars($t["name"]) ?></h4>
                            <span class="text-[9px] font-black text-mint uppercase tracking-[0.2em]">Verified Driver</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] font-black text-slate-500 uppercase tracking-tighter">Budget</span>
                        <span class="text-2xl font-black text-white"><?= $t["price"] ?> <small class="text-xs text-mint uppercase">TND</small></span>
                    </div>
                </div>

                <div class="space-y-4 mb-8">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-mint"></div>
                            <div class="w-0.5 h-8 bg-white/10"></div>
                            <div class="w-2 h-2 rounded-full bg-cyber"></div>
                        </div>
                        <div class="flex-1 space-y-2">
                            <p class="text-sm font-bold text-white"><?= htmlspecialchars($t["departure"]) ?></p>
                            <p class="text-sm font-bold text-white"><?= htmlspecialchars($t["destination"]) ?></p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="px-3 py-1.5 rounded-lg bg-white/5 text-[10px] font-bold text-slate-400 border border-white/5 flex items-center gap-2">
                            <i class="ph ph-clock-countdown text-mint"></i> <?= date("H:i", strtotime($t["date_trip"])) ?>
                        </div>
                        <div class="px-3 py-1.5 rounded-lg bg-white/5 text-[10px] font-bold text-slate-400 border border-white/5 flex items-center gap-2">
                            <i class="ph ph-users text-mint"></i> <?= $t["seats"] ?> places
                        </div>
                    </div>
                    <button onclick="openPreview(<?= htmlspecialchars(json_encode($t)) ?>)" class="bg-mint text-midnight w-10 h-10 rounded-xl flex items-center justify-center transition-all hover:scale-110 shadow-lg shadow-mint/20">
                        <i class="ph ph-eye font-black"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($trips)): ?>
        <div class="text-center py-32 space-y-6 animate-fade-in">
            <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mx-auto text-slate-700">
                <i class="ph ph-ghost text-5xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-500 uppercase tracking-tighter">Signal Perdu</h3>
            <p class="text-slate-600 font-medium">Aucune mission correspondante n'a été trouvée dans cette zone.</p>
            <a href="search.php" class="inline-block text-mint font-black uppercase text-xs tracking-widest border-b border-mint/20 pb-1 hover:border-mint transition-all">Relancer le scan</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Logic Replicated from Index for Consistency -->
<div id="preview-modal" class="fixed inset-0 z-[150] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-500">
    <div class="absolute inset-0 bg-midnight/80 backdrop-blur-[24px]" onclick="closePreview()"></div>
    <div id="preview-content" class="relative w-full max-w-2xl glass rounded-[48px] overflow-hidden border border-mint/20 shadow-[0_0_80px_rgba(45,212,191,0.15)] scale-90 opacity-0 transition-all duration-500">
        <button onclick="closePreview()" class="absolute top-8 right-8 w-12 h-12 flex items-center justify-center rounded-full bg-white/5 hover:bg-white/10 text-white transition-colors z-10">
            <i class="ph ph-x text-2xl font-bold"></i>
        </button>
        <div class="relative h-48 bg-gradient-to-br from-mint/20 via-cyber/10 to-transparent">
            <div class="absolute -bottom-12 left-12 flex items-end gap-6">
                <div class="relative">
                    <img id="p-avatar" src="" class="w-32 h-32 rounded-[32px] object-cover border-4 border-midnight shadow-2xl">
                    <div class="absolute -bottom-2 -right-2 bg-mint text-midnight px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1 shadow-lg ring-4 ring-midnight">
                        <i class="ph ph-seal-check-fill text-sm"></i> Verified
                    </div>
                </div>
                <div class="pb-2">
                    <h3 id="p-name" class="text-3xl font-black text-white tracking-tighter"></h3>
                </div>
            </div>
        </div>
        <div class="pt-20 p-12 space-y-8">
            <div class="glass p-8 rounded-[32px] border border-white/5 space-y-6">
                <p id="p-car" class="text-white font-bold text-lg"></p>
                <p id="p-dep" class="text-slate-400"></p>
                <p id="p-dest" class="text-slate-400"></p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <button class="py-4 rounded-2xl border border-white/10 text-white font-black uppercase text-xs tracking-widest">Contacter</button>
                <a id="p-book" href="" class="py-4 rounded-2xl bg-mint text-midnight font-black uppercase text-xs tracking-widest text-center">Réserver</a>
            </div>
        </div>
    </div>
</div>

<script>
    function openPreview(data) {
        const modal = document.getElementById('preview-modal');
        const content = document.getElementById('preview-content');
        document.getElementById('p-avatar').src = data.profile_pic || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.name) + '&background=0F172A&color=2DD4BF';
        document.getElementById('p-name').textContent = data.name;
        document.getElementById('p-car').textContent = "Véhicule: " + (data.car_brand || 'Non spécifié');
        document.getElementById('p-dep').textContent = "Départ: " + data.departure;
        document.getElementById('p-dest').textContent = "Destination: " + data.destination;
        document.getElementById('p-book').href = 'book.php?id=' + data.id;
        modal.classList.remove('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            content.classList.remove('opacity-0', 'scale-90');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }
    function closePreview() {
        const modal = document.getElementById('preview-modal');
        const content = document.getElementById('preview-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-90', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('opacity-0', 'pointer-events-none');
            document.body.style.overflow = '';
        }, 300);
    }
</script>

<style>
    .mesh-gradient {
        background-color: #0F172A;
        background-image: 
            radial-gradient(at 0% 0%, rgba(45, 212, 191, 0.15) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.1) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.1) 0px, transparent 50%),
            radial-gradient(at 0% 100%, rgba(45, 212, 191, 0.05) 0px, transparent 50%);
        filter: blur(80px);
        animation: mesh-move 20s infinite alternate ease-in-out;
        will-change: transform;
    }

    @keyframes mesh-move {
        0% { transform: scale(1) translate(0, 0); }
        100% { transform: scale(1.1) translate(2%, 2%); }
    }

    .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
    .animate-scale-in { animation: scaleIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    .animate-fade-up { opacity: 0; animation: fadeUp 0.8s ease-out forwards; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scaleIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    @keyframes fadeUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    #preview-content { transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1); }
</style>

<?php include "footer.php"; ?>