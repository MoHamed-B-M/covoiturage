<?php
// 1. Logic and Data Fetching FIRST
include "db.php";
include "header.php"; // This now handles session_start() safely

// Initialize $bookings as an empty array to prevent the "Undefined" warning
$bookings = [];

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];

    // Fetch bookings for the logged-in user with driver details
    $stmt = $pdo->prepare("
        SELECT B.id as booking_id, B.status, T.*, U.name as driver_name, U.phone as driver_phone
        FROM Bookings B, Trips T, Users U
        WHERE B.trip_id = T.id
          AND T.user_id = U.id
          AND B.user_id = ?
        ORDER BY T.date_trip DESC
    ");
    $stmt->execute([$userId]);
    $bookings = $stmt->fetchAll();
}
?>

<!-- Custom Mesh Background for Dashboard feel -->
<div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
    <div class="mesh-gradient h-full w-full opacity-30"></div>
</div>

<div class="py-10 space-y-12 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 px-4">
        <div>
            <span class="text-mint font-black text-[10px] uppercase tracking-[0.3em]">Espace Passager</span>
            <h1 class="text-5xl font-black tracking-tighter mt-2 text-white uppercase">Mes Réservations</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="search.php" class="px-6 py-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 hover:border-mint/30 text-xs font-black text-slate-300 uppercase tracking-widest transition-all flex items-center gap-2">
                <i class="ph ph-plus-circle text-lg"></i>
                <span>Nouveau Trajet</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4">
        <?php if (!empty($bookings)): ?>
            <div class="glass rounded-[32px] border border-white/10 overflow-hidden shadow-2xl">
                <!-- Table Wrapper for horizontal scroll on mobile -->
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/5">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Trajet</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date & Heure</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Prix</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Statut</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($bookings as $index => $b): ?>
                            <tr class="hover:bg-white/[0.04] transition-all duration-300 group">
                                <!-- Trajet -->
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-mint/10 flex items-center justify-center text-mint shrink-0 group-hover:scale-110 transition-transform duration-300">
                                            <i class="ph ph-map-pin text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white tracking-tight flex items-center gap-2">
                                                <span><?= htmlspecialchars(
                                                    $b["departure"],
                                                ) ?></span>
                                                <i class="ph ph-arrow-right text-xs text-mint"></i>
                                                <span><?= htmlspecialchars(
                                                    $b["destination"],
                                                ) ?></span>
                                            </p>
                                            <p class="text-xs text-slate-500 mt-1">Chauffeur: <?= htmlspecialchars(
                                                $b["driver_name"],
                                            ) ?> (<?= htmlspecialchars(
     $b["driver_phone"],
 ) ?>)</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date & Heure -->
                                <td class="px-8 py-6">
                                    <div class="text-slate-300 font-bold text-sm flex items-center gap-2">
                                        <i class="ph ph-calendar text-slate-500"></i>
                                        <span><?= date(
                                            "d/m/Y",
                                            strtotime($b["date_trip"]),
                                        ) ?></span>
                                        <span class="text-slate-500 font-medium">à <?= date(
                                            "H:i",
                                            strtotime($b["date_trip"]),
                                        ) ?></span>
                                    </div>
                                </td>

                                <!-- Prix -->
                                <td class="px-8 py-6">
                                    <span class="text-lg font-black text-white"><?= number_format(
                                        $b["price"],
                                        2,
                                    ) ?> <small class="text-xs text-mint">TND</small></span>
                                </td>

                                <!-- Statut Badge -->
                                <td class="px-8 py-6">
                                    <?php if (
                                        isset($b["status"]) &&
                                        $b["status"] == "active"
                                    ): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Confirmé
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/10 text-rose-400 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Annulé
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Action Buttons -->
                                <td class="px-8 py-6 text-right">
                                    <?php if (
                                        isset($b["status"]) &&
                                        $b["status"] == "active"
                                    ): ?>
                                        <a href="cancel_booking.php?id=<?= $b[
                                            "booking_id"
                                        ] ?>"
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-midnight hover:border-rose-500 hover:scale-105 active:scale-95 transition-all text-[10px] font-black uppercase tracking-widest shadow-lg"
                                           onclick="return confirm('Voulez-vous vraiment annuler cette réservation ? Cette action libérera une place pour cette mission.')">
                                            <i class="ph ph-x-circle text-base"></i>
                                            <span>Annuler</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest flex items-center gap-1.5 justify-end">
                                            <i class="ph ph-archive text-base"></i> Archivé
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <!-- Premium Empty State -->
            <div class="py-16">
                <div class="glass p-12 md:p-16 rounded-[48px] border border-white/5 text-center space-y-6 max-w-lg mx-auto shadow-2xl backdrop-blur-3xl animate-scale-in">
                    <div class="w-24 h-24 bg-white/5 rounded-[32px] flex items-center justify-center mx-auto text-slate-500 border border-white/10 shadow-inner">
                        <i class="ph ph-ticket-slash text-5xl"></i>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-black text-white uppercase tracking-tight">Aucune Réservation</h3>
                        <p class="text-slate-400 text-sm font-medium leading-relaxed max-w-sm mx-auto">
                            Vous n'avez pas encore réservé de place pour une mission de voyage. Explorez les trajets actifs et lancez-vous !
                        </p>
                    </div>
                    <div class="pt-4">
                        <a href="search.php" class="inline-flex items-center gap-3 bg-cyber-gradient text-white px-8 py-4.5 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-glow-purple">
                            <i class="ph ph-magnifying-glass text-lg"></i>
                            <span>Découvrir les missions</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Mesh background */
    .mesh-gradient {
        background-color: #0F172A;
        background-image:
            radial-gradient(at 0% 0%, rgba(45, 212, 191, 0.1) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.08) 0px, transparent 50%);
        filter: blur(80px);
    }
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }
    .animate-scale-in {
        animation: scaleIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scaleIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<?php include "footer.php"; ?>
