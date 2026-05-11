<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "db.php";
include "header.php";

$message = "";
$messageClass = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate Secure Token
        $token = bin2hex(random_bytes(16));
        $tokenHash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30); // 30 minutes

        // Save to Database
        $stmt = $pdo->prepare("UPDATE Users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?");
        $stmt->execute([$tokenHash, $expiry, $email]);

        // Reset Link
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $resetUrl = "$protocol://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=$token";

        // PHPMailer Logic
        $mail = new PHPMailer(true);
        try {
            // SMTP Settings for Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'benmohamedm715@gmail.com'; 
            $mail->Password   = 'wgpy kvvf srch ejbh'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('benmohamedm715@gmail.com', 'Rydo');
            $mail->addAddress($email);

            // Professional HTML Content
            $mail->isHTML(true);
            $mail->Subject = 'Reinitialisation de votre mot de passe - Rydo';
            
            // Modern Email Template
            $mail->Body = "
            <div style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 40px; border: 1px solid #f0f0f0; border-radius: 20px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <div style='width: 50px; height: 50px; background: #2d6df6; border-radius: 15px; display: inline-block; margin-bottom: 10px; line-height: 50px; color: white; font-weight: 900; font-size: 24px;'>R</div>
                    <h2 style='color: #1d1d1f; margin: 0;'>Rydo</h2>
                </div>
                
                <h1 style='color: #1d1d1f; font-size: 24px; font-weight: 700; margin-bottom: 20px;'>Reinitialisation du mot de passe</h1>
                
                <p style='color: #49454e; font-size: 16px; line-height: 1.6; margin-bottom: 30px;'>
                    Bonjour,<br><br>
                    Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte Rydo. Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe.
                </p>
                
                <div style='text-align: center; margin-bottom: 35px;'>
                    <a href='$resetUrl' style='background-color: #2d6df6; color: white; padding: 14px 30px; text-decoration: none; border-radius: 980px; font-weight: 600; display: inline-block;'>Reinitialiser le mot de passe</a>
                </div>
                
                <p style='color: #79747e; font-size: 14px; line-height: 1.6; margin-bottom: 20px;'>
                    Ce lien expirera dans <strong>30 minutes</strong>. Si vous n'avez pas demandé ce changement, vous pouvez ignorer cet email en toute sécurité.
                </p>
                
                <hr style='border: 0; border-top: 1px solid #f0f0f0; margin-bottom: 20px;'>
                
                <p style='color: #9c9a9e; font-size: 12px; text-align: center;'>
                    &copy; " . date('Y') . " Rydo. Tous droits réservés.
                </p>
            </div>";

            $mail->AltBody = "Cliquez sur ce lien pour réinitialiser votre mot de passe: $resetUrl";

            $mail->send();
            $message = "Un lien de réinitialisation a été envoyé à votre adresse email.";
            $messageClass = "alert-success";
        } catch (Exception $e) {
            // DEBUG MODE: If SMTP fails, show the link for testing purposes
            $message = "Erreur SMTP: Le lien ne peut pas être envoyé par email. <br><br><strong>Lien de test:</strong> <br><a href='$resetUrl' class='alert-link'>$resetUrl</a><br><br><small>Note: Assurez-vous de configurer vos identifiants SMTP dans forgot_password.php.</small>";
            $messageClass = "alert-warning";
        }
    } else {
        $message = "Aucun utilisateur trouvé avec cette adresse email.";
        $messageClass = "alert-danger";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5 apple-card p-5 mt-5 fade-in">
        <h2 class="fw-bold text-center mb-2">Mot de passe oublié</h2>
        <p class="text-secondary text-center mb-4 small">Entrez votre email pour recevoir un lien de réinitialisation.</p>

        <?php if ($message): ?>
            <div class="alert <?= $messageClass ?> small fade-in"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="form-label">Adresse Email</label>
                <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" required>
            </div>
            <button class="btn btn-apple w-100 py-2 mb-3">Envoyer le lien</button>
            <div class="text-center">
                <a href="login.php" class="text-decoration-none small text-secondary">Retour à la connexion</a>
            </div>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>
