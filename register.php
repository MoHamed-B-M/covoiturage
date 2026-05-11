<?php
include "db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO Users (name, email, password, phone) VALUES (?, ?, ?, ?)",
    );
    $stmt->execute([$_POST["name"], $_POST["email"], $pass, $_POST["phone"]]);
    header("Location: login.php");
    exit();
}

include "header.php";
?>

<div class="flex items-center justify-center min-h-[90vh] py-12">
    <div class="glass max-w-lg w-full rounded-[40px] p-10 md:p-12 shadow-2xl border border-white/10 relative overflow-hidden">
        <!-- Decorative Glow -->
        <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-mint/10 rounded-full filter blur-[60px]"></div>
        
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-mint/10 rounded-2xl flex items-center justify-center text-mint mx-auto mb-6 shadow-glow-mint">
                <i class="bi bi-person-plus-fill text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black tracking-tighter uppercase">Recruitment</h2>
            <p class="text-slate-500 text-sm mt-2 uppercase tracking-widest font-bold">Join the Rydo Network</p>
        </div>

        <form method="POST" class="space-y-6">
            <!-- Full Name -->
            <div class="premium-input-group relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 transition-colors group-focus-within:text-mint">
                    <i class="bi bi-person-fill"></i>
                </div>
                <input type="text" name="name" id="name" placeholder=" " class="premium-input h-16 w-full rounded-2xl pl-12 pr-6 pt-5 text-sm font-bold text-white transition-all bg-transparent focus:ring-0" required>
                <label for="name" class="floating-label absolute left-12 top-5 text-sm font-bold text-slate-500 uppercase tracking-widest">Full Name</label>
            </div>

            <!-- Email -->
            <div class="premium-input-group relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 transition-colors group-focus-within:text-mint">
                    <i class="bi bi-envelope-at-fill"></i>
                </div>
                <input type="email" name="email" id="email" placeholder=" " class="premium-input h-16 w-full rounded-2xl pl-12 pr-6 pt-5 text-sm font-bold text-white transition-all bg-transparent focus:ring-0" required>
                <label for="email" class="floating-label absolute left-12 top-5 text-sm font-bold text-slate-500 uppercase tracking-widest">Email Address</label>
            </div>

            <!-- Phone -->
            <div class="premium-input-group relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 transition-colors group-focus-within:text-mint">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <input type="text" name="phone" id="phone" placeholder=" " class="premium-input h-16 w-full rounded-2xl pl-12 pr-6 pt-5 text-sm font-bold text-white transition-all bg-transparent focus:ring-0" required>
                <label for="phone" class="floating-label absolute left-12 top-5 text-sm font-bold text-slate-500 uppercase tracking-widest">Contact Signal</label>
            </div>

            <!-- Password -->
            <div class="premium-input-group relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 transition-colors group-focus-within:text-mint">
                    <i class="bi bi-key-fill"></i>
                </div>
                <input type="password" name="password" id="password" placeholder=" " class="premium-input h-16 w-full rounded-2xl pl-12 pr-6 pt-5 text-sm font-bold text-white transition-all bg-transparent focus:ring-0" required minlength="6">
                <label for="password" class="floating-label absolute left-12 top-5 text-sm font-bold text-slate-500 uppercase tracking-widest">Access Key</label>
            </div>

            <button type="submit" class="w-full bg-mint text-midnight py-4 rounded-2xl font-black uppercase text-sm shadow-glow-mint hover:scale-[1.02] active:scale-95 transition-all mt-4">
                Confirm Registration
            </button>
            
            <div class="text-center pt-6">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                    Already an Agent? <a href="login.php" class="text-cyber hover:underline">Return to Base</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>
