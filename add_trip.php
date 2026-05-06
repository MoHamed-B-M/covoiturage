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

<div class="container-fluid fade-in">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <!-- Material 3 Expressive Card with 32px radius[cite: 2] -->
        <div class="col-md-6 apple-card shadow-lg p-5">
            <h2 class="fw-bold mb-4">Proposer un trajet</h2>

            <?php if (isset($msg)): ?>
                <div class="alert alert-success border-0 rounded-4"><?= $msg ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Départ</label>
                        <input type="text" name="dep" class="form-control rounded-4 p-3" placeholder="Ex: Sidi Bouzid" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Destination</label>
                        <input type="text" name="dest" class="form-control rounded-4 p-3" placeholder="Ex: Tunis" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Date et heure</label>
                        <input type="datetime-local" name="date" class="form-control rounded-4 p-3" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Nombre de places</label>
                        <input type="number" name="seats" class="form-control rounded-4 p-3" placeholder="Ex: 3" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Prix (TND)</label>
                        <input type="number" step="0.01" name="price" class="form-control rounded-4 p-3" placeholder="Ex: 15.00" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Marque de la voiture</label>
                        <input type="text" name="car_brand" class="form-control rounded-4 p-3" placeholder="Ex: Volkswagen Golf 7" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Services / Offres (séparés par des virgules)</label>
                        <input type="text" name="offers" class="form-control rounded-4 p-3" placeholder="Ex: Climatisation, Musique, Non-fumeur">
                    </div>
                    <div class="col-12 mt-4">
                        <!-- Button styled with Material 3 rounded-pill[cite: 2] -->
                        <button class="btn btn-primary w-100 rounded-pill py-3 fw-bold">
                            Publier le trajet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
