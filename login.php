<?php
include "db.php";
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Admin override
    if ($_POST["email"] === "admin@gmail.com" && $_POST["password"] === "admin") {
        $stmt = $pdo->prepare("SELECT id FROM Users WHERE email = ?");
        $stmt->execute([$_POST["email"]]);
        $adminUser = $stmt->fetch();
        
        $_SESSION["user_id"] = $adminUser ? $adminUser["id"] : "admin";
        $_SESSION["user_name"] = "Admin";
        $_SESSION["role"] = "admin";
        header("Location: admin_dashboard.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$_POST["email"]]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST["password"], $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        header("Location: index.php");
        exit();
    } else {
        $error = "Access Denied: Invalid Credentials";
    }
}

include "header.php";
?>

<div class="flex items-center justify-center min-h-[80vh]">
    <div class="glass max-w-md w-full rounded-[40px] p-10 md:p-12 shadow-2xl border border-white/10 relative overflow-hidden">
        <!-- Decorative Glow -->
        <div class="absolute -top-20 -right-20 w-48 h-48 bg-cyber/10 rounded-full filter blur-[60px]"></div>
        
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-cyber-gradient rounded-2xl flex items-center justify-center text-white mx-auto mb-6 shadow-glow-purple">
                <i class="bi bi-shield-lock-fill text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black tracking-tighter uppercase">Authorized Entry</h2>
            <p class="text-slate-500 text-sm mt-2 uppercase tracking-widest font-bold">Secure Login Protocol</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider mb-8 text-center animate-pulse">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <!-- Email -->
            <div class="premium-input-group relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 transition-colors group-focus-within:text-cyber">
                    <i class="bi bi-envelope-at-fill"></i>
                </div>
                <input type="email" name="email" id="email" placeholder=" " class="premium-input h-16 w-full rounded-2xl pl-12 pr-6 pt-5 text-sm font-bold text-white transition-all bg-transparent focus:ring-0" required>
                <label for="email" class="floating-label absolute left-12 top-5 text-sm font-bold text-slate-500 uppercase tracking-widest">Email Address</label>
            </div>

            <!-- Password -->
            <div class="premium-input-group relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 transition-colors group-focus-within:text-cyber">
                    <i class="bi bi-key-fill"></i>
                </div>
                <input type="password" name="password" id="password" placeholder=" " class="premium-input h-16 w-full rounded-2xl pl-12 pr-6 pt-5 text-sm font-bold text-white transition-all bg-transparent focus:ring-0" required>
                <label for="password" class="floating-label absolute left-12 top-5 text-sm font-bold text-slate-500 uppercase tracking-widest">Access Key</label>
                
                <div class="text-right mt-2">
                    <a href="forgot_password.php" class="text-[10px] font-black uppercase text-slate-500 hover:text-cyber transition-colors tracking-widest">Forgot Password?</a>
                </div>
            </div>

            <button type="submit" class="w-full bg-cyber-gradient text-white py-4 rounded-2xl font-black uppercase text-sm shadow-glow-purple hover:scale-[1.02] active:scale-95 transition-all">
                Initiate Login
            </button>
            
            <div class="text-center pt-6">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                    New Agent? <a href="register.php" class="text-mint hover:underline">Request Recruitment</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>
