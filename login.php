<?php
// 1. Database and Session must be handled BEFORE any HTML output[cite: 12, 13]
include "db.php";
session_start();

// Redirect if already logged in (Optional but recommended)
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
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

        // 2. header() only works if no HTML has been sent to the browser yet
        header("Location: dashboard.php");
        exit(); // Always exit after a redirect
    } else {
        $error = "Identifiants invalides";
    }
}

// 3. Now include the visual header after the logic[cite: 12]
include "header.php";
?>

<div class="row justify-content-center">
    <div class="col-md-4 apple-card p-5 mt-5">
        <h2 class="fw-bold text-center mb-4">Connexion</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger small"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-4" placeholder="Mot de passe" required>
            <button class="btn btn-apple w-100 py-2">Se connecter</button>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>
