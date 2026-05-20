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

<div class="flex items-center justify-center min-h-[80vh]">
    <div class="glass max-w-md w-full rounded-[40px] p-10 md:p-12 shadow-2xl border border-white/10 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-48 h-48 bg-mint/10 rounded-full filter blur-[60px]"></div>

        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-to-br from-mint to-teal-600 rounded-2xl flex items-center justify-center text-midnight mx-auto mb-6 shadow-glow-mint">
                <i class="ph ph-envelope-open text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black tracking-tighter uppercase">Mot de passe oublié</h2>
            <p class="text-slate-500 text-sm mt-2 uppercase tracking-widest font-bold">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        </div>

        <?php if ($message): ?>
            <?php
                $msgType = 'red';
                if (strpos($messageClass, 'success') !== false) $msgType = 'emerald';
                elseif (strpos($messageClass, 'warning') !== false) $msgType = 'amber';
            ?>
            <div class="bg-<?= $msgType ?>-500/10 border border-<?= $msgType ?>-500/20 text-<?= $msgType ?>-400 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider mb-8 text-center animate-pulse">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="premium-input-group relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 transition-colors group-focus-within:text-mint">
                    <i class="ph ph-envelope-simple"></i>
                </div>
                <input type="email" name="email" id="email" placeholder=" " class="premium-input h-16 w-full rounded-2xl pl-12 pr-6 pt-5 text-sm font-bold text-white transition-all bg-transparent focus:ring-0" required>
                <label for="email" class="floating-label absolute left-12 top-5 text-sm font-bold text-slate-500 uppercase tracking-widest">Adresse Email</label>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-mint to-teal-600 text-midnight py-4 rounded-2xl font-black uppercase text-sm shadow-glow-mint hover:scale-[1.02] active:scale-95 transition-all">
                Envoyer le lien
            </button>

            <div class="text-center pt-6">
                <a href="login.php" class="text-slate-500 text-xs font-bold uppercase tracking-widest hover:text-white transition-colors">
                    <i class="ph ph-arrow-left me-1"></i> Retour à la connexion
                </a>
            </div>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>
