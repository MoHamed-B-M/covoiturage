<?php include "header.php";
include "db.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO Users (name, email, password) VALUES (?, ?, ?)",
    );
    $stmt->execute([$_POST["name"], $_POST["email"], $pass]);
    header("Location: login.php");
}
?>
<div class="row justify-content-center">
    <div class="col-md-4 apple-card p-5 mt-5">
        <h2 class="fw-bold text-center mb-4">Créer un compte</h2>
        <form method="POST">
            <input type="text" name="name" class="form-control mb-3" placeholder="Nom complet" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Adresse email" required>
            <input type="password" name="password" class="form-control mb-4" placeholder="Mot de passe" required>
            <button class="btn btn-apple w-100 py-2">S'inscrire</button>
        </form>
    </div>
</div>
<?php include "footer.php"; ?>
