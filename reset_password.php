<?php
include "db.php";
include "header.php";

$message = "";
$messageClass = "";
$showForm = false;

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    $tokenHash = hash("sha256", $token);

    // Validate Token
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()");
    $stmt->execute([$tokenHash]);
    $user = $stmt->fetch();

    if ($user) {
        $showForm = true;
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $newPassword = $_POST["password"];
            $confirmPassword = $_POST["confirm_password"];

            if ($newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update Password and Clear Token
                $stmt = $pdo->prepare("UPDATE Users SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?");
                $stmt->execute([$hashedPassword, $user["id"]]);

                $message = "Votre mot de passe a été mis à jour avec succès. <a href='login.php' class='alert-link'>Connectez-vous ici</a>.";
                $messageClass = "alert-success";
                $showForm = false;
            } else {
                $message = "Les mots de passe ne correspondent pas.";
                $messageClass = "alert-danger";
            }
        }
    } else {
        $message = "Lien invalide ou expiré. Veuillez faire une <a href='forgot_password.php' class='alert-link'>nouvelle demande</a>.";
        $messageClass = "alert-danger";
    }
} else {
    header("Location: forgot_password.php");
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-5 apple-card p-5 mt-5 fade-in">
        <h2 class="fw-bold text-center mb-4">Nouveau mot de passe</h2>

        <?php if ($message): ?>
            <div class="alert <?= $messageClass ?> small mb-4 fade-in"><?= $message ?></div>
        <?php endif; ?>

        <?php if ($showForm): ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
                </div>
                <div class="mb-4">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required minlength="6">
                </div>
                <button class="btn btn-apple w-100 py-2">Réinitialiser le mot de passe</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include "footer.php"; ?>
