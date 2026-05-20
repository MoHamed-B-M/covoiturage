<?php
include "db.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare(
        "INSERT INTO Trips (user_id, departure, destination, date_trip, seats, price, car_brand, offers) VALUES (?,?,?,?,?,?,?,?)"
    );
    $stmt->execute([
        $_SESSION["user_id"],
        $_POST["dep"],
        $_POST["dest"],
        $_POST["date"],
        $_POST["seats"],
        $_POST["price"],
        $_POST["car_brand"],
        $_POST["offers"]
    ]);
    header("Location: index.php"); 
    exit();
}

include "header.php";
?>

<div class="flex justify-center items-center py-12">
    <!-- Material 3 Expressive Card -->
    <div class="max-w-2xl w-full glass rounded-[40px] p-8 md:p-12 shadow-2xl border border-white/5">
        <div class="mb-10 text-center">
            <h2 class="text-4xl font-black tracking-tighter text-white mb-2">Proposer un trajet</h2>
            <p class="text-slate-400 font-medium">Partagez votre route et réduisez vos frais</p>
        </div>

        <?php if (isset($msg)): ?>
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
                <i class="ph ph-check-circle text-xl"></i>
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Departure -->
                <div class="space-y-2 group">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-mint transition-colors">Départ</label>
                    <div class="relative">
                        <i class="ph ph-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                        <input type="text" name="dep" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-4 focus:ring-mint/20 focus:border-mint/50 focus:bg-white/10 transition-all duration-500" 
                            placeholder="Ex: Sidi Bouzid" required>
                    </div>
                </div>

                <!-- Destination -->
                <div class="space-y-2 group">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-mint transition-colors">Destination</label>
                    <div class="relative">
                        <i class="ph ph-navigation-arrow absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                        <input type="text" name="dest" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-4 focus:ring-mint/20 focus:border-mint/50 focus:bg-white/10 transition-all duration-500" 
                            placeholder="Ex: Tunis" required>
                    </div>
                </div>
            </div>

            <!-- Date and Time -->
            <div class="space-y-2 group">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-cyber transition-colors">Date et heure</label>
                <div class="relative">
                    <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-cyber group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="datetime-local" name="date" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white focus:outline-none focus:ring-4 focus:ring-cyber/20 focus:border-cyber/50 focus:bg-white/10 transition-all duration-500" 
                        required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Seats -->
                <div class="space-y-2 group">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-indigo-400 transition-colors">Nombre de places</label>
                    <div class="relative">
                        <i class="ph ph-users absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-indigo-400 group-focus-within:scale-110 transition-all duration-500"></i>
                        <input type="number" name="seats" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/50 focus:bg-white/10 transition-all duration-500" 
                            placeholder="Ex: 3" required>
                    </div>
                </div>

                <!-- Price -->
                <div class="space-y-2 group">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-amber-400 transition-colors">Prix (TND)</label>
                    <div class="relative">
                        <i class="ph ph-currency-circle-dollar absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-amber-400 group-focus-within:scale-110 transition-all duration-500"></i>
                        <input type="number" step="0.01" name="price" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500/50 focus:bg-white/10 transition-all duration-500" 
                            placeholder="Ex: 15.00" required>
                    </div>
                </div>
            </div>

            <!-- Car Brand -->
            <div class="space-y-2 group">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-blue-400 transition-colors">Marque de la voiture</label>
                <div class="relative">
                    <i class="ph ph-car absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-blue-400 group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="text" name="car_brand" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500/50 focus:bg-white/10 transition-all duration-500" 
                        placeholder="Ex: Volkswagen Golf 7" required>
                </div>
            </div>

            <!-- Offers -->
            <div class="space-y-2 group">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-rose-400 transition-colors">Services / Offres</label>
                <div class="relative">
                    <i class="ph ph-sparkle absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-rose-400 group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="text" name="offers" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-4 focus:ring-rose-500/20 focus:border-rose-500/50 focus:bg-white/10 transition-all duration-500" 
                        placeholder="Ex: Climatisation, Musique, Non-fumeur">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" 
                    class="w-full py-5 rounded-[20px] bg-gradient-to-r from-mint via-cyber to-indigo-600 text-midnight font-black uppercase tracking-widest hover:scale-[1.02] hover:-translate-y-1 active:scale-95 transition-all duration-300 shadow-xl shadow-mint/20 flex items-center justify-center gap-3">
                    <span>Publier le trajet</span>
                    <i class="ph ph-rocket-launch text-2xl"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Custom bouncy focus transition */
    input:focus {
        transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
    }
</style>

<?php include "footer.php"; ?>
