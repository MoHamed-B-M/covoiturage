<?php
// 1. Logic and Redirects MUST come before any HTML output
include "header_2.php"; // Using the verbatim file name as requested[cite: 3]
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare(
        "INSERT INTO Trips (user_id, departure, destination, date_trip, seats, price) VALUES (?,?,?,?,?,?)"
    );
    $stmt->execute([
        $_SESSION["user_id"],
        $_POST["dep"],
        $_POST["dest"],
        $_POST["date"],
        $_POST["seats"],
        $_POST["price"],
    ]);
    header("Location: index.php"); // Redirect to home after success
    exit();
}
// Removed the redundant second include "header.php" and stray ?> tags
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
                        <input type="text" name="dep" class="form-control rounded-4 p-3" placeholder="Départ" required>
                    </div>
                    <div class="col-6">
                        <input type="text" name="dest" class="form-control rounded-4 p-3" placeholder="Destination" required>
                    </div>
                    <div class="col-12">
                        <label class="small text-muted ms-1 mb-2">Date et heure</label>
                        <input type="datetime-local" name="date" class="form-control rounded-4 p-3" required>
                    </div>
                    <div class="col-6">
                        <input type="number" name="seats" class="form-control rounded-4 p-3" placeholder="Places" required>
                    </div>
                    <div class="col-6">
                        <input type="number" step="0.01" name="price" class="form-control rounded-4 p-3" placeholder="Prix (TND)" required>
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

<?php include "footer_2.php"; // Reference verbatim as requested[cite: 3] ?>
