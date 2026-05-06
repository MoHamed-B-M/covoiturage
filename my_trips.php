<?php
// 1. Logic and Auth MUST come first. No HTML or includes before this![cite: 12, 16]
include "db.php";

// We check if session is already active in our new header,
// but for redirects, we check it here first.[cite: 14]
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// 2. Fetch data
$stmt = $pdo->prepare("SELECT * FROM Trips WHERE user_id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$trips = $stmt->fetchAll();

// 3. Now include the visual header[cite: 12, 16]
include "header.php";
?>

<h2 class="fw-bold mb-4">Mes Annonces Proposées</h2>

<div class="row g-4">
    <?php foreach ($trips as $t): ?>
    <div class="col-md-4">
        <div class="apple-card p-4">
            <h4 class="fw-bold mb-1">
                <?= htmlspecialchars($t["departure"]) ?> → <?= htmlspecialchars(
     $t["destination"],
 ) ?>
            </h4>
            <p class="text-muted small">
                <?= date("d/m/Y", strtotime($t["date_trip"])) ?>
            </p>
            <div class="mt-3 pt-3 border-top d-flex justify-content-between">
                <span>Places: <strong><?= $t["seats"] ?></strong></span>
                <span class="text-primary fw-bold"><?= $t["price"] ?> TND</span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($trips)): ?>
        <div class="col-12 text-center py-5 text-muted">
            Vous n'avez pas encore proposé de trajet.
        </div>
    <?php endif; ?>
</div>

<?php include "footer.php"; ?>
