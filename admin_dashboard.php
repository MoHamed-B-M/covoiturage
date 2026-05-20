<?php
include "header.php";
include "db.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit();
}

// Handle User Deletion
if (isset($_GET["delete_user"])) {
    $id = $_GET["delete_user"];
    try {
        $pdo->beginTransaction();
        $pdo->prepare("DELETE FROM Bookings WHERE trip_id IN (SELECT id FROM Trips WHERE user_id = ?)")->execute([$id]);
        $pdo->prepare("DELETE FROM Bookings WHERE user_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM Trips WHERE user_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM Users WHERE id = ?")->execute([$id]);
        $pdo->commit();
        $success = "Utilisateur supprimé avec succès.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erreur: " . $e->getMessage();
    }
}

// Handle User Update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_user"])) {
    $id = $_POST["user_id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $role = $_POST["role"] ?? "user";
    $stmt = $pdo->prepare("UPDATE Users SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$name, $email, $role, $id]);
    $success = "Utilisateur mis à jour.";
}

// Stats Queries
$total_users = $pdo->query("SELECT COUNT(*) FROM Users")->fetchColumn();
$active_trips = $pdo->query("SELECT COUNT(*) FROM Trips")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(price) FROM Trips")->fetchColumn() ?: 0;

$users = $pdo->query("SELECT * FROM Users WHERE name != 'admin' ORDER BY id DESC")->fetchAll();
?>

<div class="py-10 space-y-12">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 px-4">
        <div>
            <span class="text-mint font-black text-[10px] uppercase tracking-[0.3em]">Control Center</span>
            <h1 class="text-5xl font-black tracking-tighter mt-2 text-white uppercase">Admin Dashboard</h1>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-bold text-slate-400">
                <span class="text-mint">●</span> System Online
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
        <!-- Total Users -->
        <div class="glass p-8 rounded-[32px] border border-white/5 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-mint/5 rounded-full group-hover:bg-mint/10 transition-colors"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-mint/10 flex items-center justify-center text-mint shadow-glow-mint">
                    <i class="ph ph-users-three text-3xl"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Total Agents</span>
                    <p class="text-3xl font-black text-white"><?= $total_users ?></p>
                </div>
            </div>
        </div>

        <!-- Active Missions -->
        <div class="glass p-8 rounded-[32px] border border-white/5 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-cyber/5 rounded-full group-hover:bg-cyber/10 transition-colors"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-cyber/10 flex items-center justify-center text-cyber shadow-glow-purple">
                    <i class="ph ph-rocket-launch text-3xl"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Active Missions</span>
                    <p class="text-3xl font-black text-white"><?= $active_trips ?></p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="glass p-8 rounded-[32px] border border-white/5 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full group-hover:bg-amber-500/10 transition-colors"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 shadow-[0_0_20px_rgba(245,158,11,0.2)]">
                    <i class="ph ph-currency-circle-dollar text-3xl"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Total Volume</span>
                    <p class="text-3xl font-black text-white"><?= number_format($total_revenue, 0) ?> <small class="text-sm font-bold opacity-50">TND</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    <?php if (isset($success)): ?>
    <div class="mx-4 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3 animate-spring-down">
        <i class="ph ph-check-circle text-xl"></i> <?= $success ?>
    </div>
    <?php endif; ?>

    <!-- User Management Table -->
    <div class="mx-4 glass rounded-[40px] border border-white/10 overflow-hidden shadow-2xl">
        <div class="px-8 py-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
            <h3 class="text-xl font-black tracking-tight text-white uppercase">Agent Directory</h3>
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input type="text" placeholder="Search agents..." class="bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs font-bold text-white focus:outline-none focus:border-mint/50 transition-all">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/2">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Agent</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Contact Signal</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Security Level</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($users as $user): 
                        $initials = strtoupper(substr($user["name"], 0, 1) . substr(explode(' ', $user["name"])[1] ?? '', 0, 1));
                        $colors = ['bg-mint/10 text-mint', 'bg-cyber/10 text-cyber', 'bg-blue-500/10 text-blue-400', 'bg-amber-500/10 text-amber-400'];
                        $randomColor = $colors[array_rand($colors)];
                    ?>
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <?php if ($user["profile_pic"]): ?>
                                    <img src="<?= $user["profile_pic"] ?>" class="w-12 h-12 rounded-full object-cover ring-2 ring-white/10 group-hover:ring-mint/30 transition-all">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-full <?= $randomColor ?> flex items-center justify-center font-black text-xs ring-2 ring-white/5 group-hover:ring-white/20 transition-all">
                                        <?= $initials ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-bold text-white"><?= htmlspecialchars($user["name"]) ?></p>
                                    <p class="text-xs text-slate-500 font-medium"><?= htmlspecialchars($user["email"]) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-300"><?= htmlspecialchars($user["phone"]) ?></span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <?php if (($user["role"] ?? "user") === "admin"): ?>
                                <span class="px-3 py-1 rounded-full bg-cyber/10 text-cyber text-[9px] font-black uppercase tracking-widest border border-cyber/20">Admin</span>
                            <?php else: ?>
                                <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-[9px] font-black uppercase tracking-widest border border-blue-500/20">Agent</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditDrawer(<?= htmlspecialchars(json_encode($user)) ?>)" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:bg-mint/20 hover:text-mint hover:scale-110 active:scale-95 transition-all">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <a href="?delete_user=<?= $user["id"] ?>" onclick="return confirm('Terminer cet agent ?')" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:bg-rose-500/20 hover:text-rose-500 hover:scale-110 active:scale-95 transition-all">
                                    <i class="ph ph-trash text-xl"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Side Drawer Overlay -->
<div id="drawer-overlay" class="fixed inset-0 z-[200] opacity-0 pointer-events-none transition-all duration-500">
    <div class="absolute inset-0 bg-midnight/60 backdrop-blur-xl" onclick="closeEditDrawer()"></div>
    
    <!-- Drawer Panel -->
    <div id="edit-drawer" class="absolute right-0 top-0 h-full w-full max-w-md bg-midnight border-l border-white/10 shadow-[-20px_0_50px_rgba(0,0,0,0.5)] translate-x-full transition-all duration-500 flex flex-col">
        <div class="p-8 border-b border-white/5 flex items-center justify-between">
            <div>
                <span class="text-mint font-black text-[10px] uppercase tracking-[0.3em]">Operation</span>
                <h3 class="text-2xl font-black text-white tracking-tighter uppercase">Modify Agent</h3>
            </div>
            <button onclick="closeEditDrawer()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/5 text-slate-500 transition-colors">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>

        <form method="POST" class="p-8 flex-1 space-y-8">
            <input type="hidden" name="user_id" id="edit_id">
            <input type="hidden" name="update_user" value="1">

            <!-- Name -->
            <div class="space-y-2 group">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-mint transition-colors">Nom de l'agent</label>
                <div class="relative">
                    <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint transition-all"></i>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white focus:outline-none focus:ring-4 focus:ring-mint/20 focus:border-mint/50 transition-all">
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-2 group">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-cyber transition-colors">Identifiant E-mail</label>
                <div class="relative">
                    <i class="ph ph-at absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-cyber transition-all"></i>
                    <input type="email" name="email" id="edit_email" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white focus:outline-none focus:ring-4 focus:ring-cyber/20 focus:border-cyber/50 transition-all">
                </div>
            </div>

            <!-- Role -->
            <div class="space-y-2 group">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-blue-400 transition-colors">Niveau de sécurité</label>
                <div class="relative">
                    <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-blue-400 transition-all"></i>
                    <select name="role" id="edit_role" class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500/50 appearance-none transition-all font-bold">
                        <option value="user" class="bg-midnight">Agent (Standard)</option>
                        <option value="admin" class="bg-midnight">Admin (Full Access)</option>
                    </select>
                </div>
            </div>

            <div class="pt-8">
                <button type="submit" class="w-full py-5 rounded-2xl bg-mint text-midnight font-black uppercase text-sm tracking-widest shadow-glow-mint hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span>Mettre à jour l'agent</span>
                    <i class="ph ph-arrows-clockwise text-xl"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditDrawer(user) {
        document.getElementById('edit_id').value = user.id;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role || 'user';

        const overlay = document.getElementById('drawer-overlay');
        const drawer = document.getElementById('edit-drawer');

        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        
        setTimeout(() => {
            drawer.classList.remove('translate-x-full');
            drawer.classList.add('translate-x-0');
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }

    function closeEditDrawer() {
        const overlay = document.getElementById('drawer-overlay');
        const drawer = document.getElementById('edit-drawer');

        drawer.classList.remove('translate-x-0');
        drawer.classList.add('translate-x-full');
        
        setTimeout(() => {
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            document.body.style.overflow = '';
        }, 300);
    }
</script>

<style>
    #edit-drawer {
        transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes spring-down {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    .animate-spring-down {
        animation: spring-down 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
</style>

<?php include "footer.php"; ?>
