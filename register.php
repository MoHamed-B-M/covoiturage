<?php
include "db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM Users WHERE email = ?");
    $stmt->execute([$_POST["email"]]);
    if ($stmt->fetch()) {
        http_response_code(409); // Conflict
        $error = "Cette adresse e-mail est déjà associée à un compte. Souhaitez-vous vous connecter ?";
    } else {
        $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "INSERT INTO Users (name, email, password, phone) VALUES (?, ?, ?, ?)",
        );
        $stmt->execute([$_POST["name"], $_POST["email"], $pass, $_POST["phone"]]);
        header("Location: login.php");
        exit();
    }
}

include "header.php";
?>

<div class="flex items-center justify-center min-h-[90vh] py-12 px-4 relative">
    
    <!-- Material 3 Springy Alert -->
    <?php if ($error): ?>
    <div id="alert-conflict" class="fixed top-24 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md animate-spring-down">
        <div class="bg-amber-500/10 border-2 border-amber-500/50 backdrop-blur-xl p-6 rounded-[32px] shadow-2xl flex items-start gap-4 ring-8 ring-amber-500/5">
            <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center text-midnight shrink-0 shadow-lg shadow-amber-500/20">
                <i class="ph ph-warning-octagon text-2xl font-bold"></i>
            </div>
            <div class="space-y-2">
                <p class="text-amber-200 font-bold leading-tight">
                    <?= $error ?>
                </p>
                <a href="login.php" class="inline-flex items-center gap-2 text-amber-500 font-black text-xs uppercase tracking-widest hover:underline">
                    Se connecter maintenant <i class="ph ph-arrow-right font-bold"></i>
                </a>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-amber-500/50 hover:text-amber-500 transition-colors">
                <i class="ph ph-x text-xl font-bold"></i>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <div class="glass max-w-lg w-full rounded-[40px] p-10 md:p-12 shadow-2xl border border-white/10 relative overflow-hidden">
        <!-- Decorative Glow -->
        <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-mint/10 rounded-full filter blur-[60px]"></div>
        
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-mint/10 rounded-2xl flex items-center justify-center text-mint mx-auto mb-6 shadow-glow-mint">
                <i class="ph ph-user-plus text-3xl font-bold"></i>
            </div>
            <h2 class="text-3xl font-black tracking-tighter uppercase">Recrutement</h2>
            <p class="text-slate-500 text-sm mt-2 uppercase tracking-widest font-bold">Rejoignez le réseau Rydo</p>
        </div>

        <form method="POST" class="space-y-6">
            <!-- Full Name -->
            <div class="space-y-2 group">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-mint transition-colors">Nom complet</label>
                <div class="relative">
                    <i class="ph ph-user absolute left-5 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="text" name="name" placeholder="Ex: Jean Dupont" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-white focus:outline-none focus:ring-4 focus:ring-mint/20 focus:border-mint/50 transition-all duration-500" required>
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-2 group">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-mint transition-colors">Adresse E-mail</label>
                <div class="relative">
                    <i class="ph ph-at absolute left-5 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="email" name="email" placeholder="agent@rydo.com" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-white focus:outline-none focus:ring-4 focus:ring-mint/20 focus:border-mint/50 transition-all duration-500" required>
                </div>
            </div>

            <!-- Phone -->
            <div class="space-y-2 group">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-mint transition-colors">Signal de contact</label>
                <div class="relative">
                    <i class="ph ph-phone absolute left-5 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="text" name="phone" placeholder="+216 -- --- ---" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-white focus:outline-none focus:ring-4 focus:ring-mint/20 focus:border-mint/50 transition-all duration-500" required>
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-2 group">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1 group-focus-within:text-mint transition-colors">Clé d'accès</label>
                <div class="relative">
                    <i class="ph ph-lock-key absolute left-5 top-1/2 -translate-y-1/2 text-xl text-slate-500 group-focus-within:text-mint group-focus-within:scale-110 transition-all duration-500"></i>
                    <input type="password" name="password" placeholder="••••••••" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-white focus:outline-none focus:ring-4 focus:ring-mint/20 focus:border-mint/50 transition-all duration-500" required minlength="6">
                </div>
            </div>

            <button type="submit" class="w-full bg-mint text-midnight py-5 rounded-[20px] font-black uppercase text-sm shadow-glow-mint hover:scale-[1.02] active:scale-95 transition-all mt-4 flex items-center justify-center gap-3">
                <span>Confirmer l'inscription</span>
                <i class="ph ph-check-circle text-xl"></i>
            </button>
            
            <div class="text-center pt-6">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                    Déjà un agent ? <a href="login.php" class="text-cyber hover:underline">Retour à la base</a>
                </p>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes spring-down {
        0% { transform: translate(-50%, -100%) scale(0.9); opacity: 0; }
        60% { transform: translate(-50%, 20px) scale(1.02); opacity: 1; }
        100% { transform: translate(-50%, 0) scale(1); opacity: 1; }
    }
    .animate-spring-down {
        animation: spring-down 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    input {
        transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
    }
</style>

<?php include "footer.php"; ?>
