<?php include "header.php";
include "db.php";
$trips = [];
if (isset($_GET["q"])) {
    $q = "%" . $_GET["q"] . "%";
    $stmt = $pdo->prepare(
        "SELECT * FROM Trips WHERE (departure LIKE ? OR destination LIKE ?) AND seats > 0",
    );
    $stmt->execute([$q, $q]);
    $trips = $stmt->fetchAll();
}
?>
<h2 class="fw-bold mb-4">Rechercher un trajet</h2>
<form method="GET" class="mb-5 d-flex gap-2">
    <input type="text" name="q" class="form-control form-control-lg border-0 shadow-sm" placeholder="Où voulez-vous aller ?" value="<?= htmlspecialchars(
        $_GET["q"] ?? "",
    ) ?>">
    <button class="btn btn-apple px-4">Rechercher</button>
</form>

<div class="row g-4">
    <?php foreach ($trips as $t): ?>
    <div class="col-md-4">
        <div class="apple-card p-4">
            <h4 class="fw-bold mb-1"><?= htmlspecialchars(
                $t["departure"],
            ) ?> → <?= htmlspecialchars($t["destination"]) ?></h4>
            <p class="text-muted small mb-3"><?= date(
                "d M Y - H:i",
                strtotime($t["date_trip"]),
            ) ?></p>
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <span class="fw-bold h4 m-0"><?= $t["price"] ?> TND</span>
                <a href="book.php?id=<?= $t[
                    "id"
                ] ?>" class="btn btn-apple btn-sm">Réserver</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (isset($_GET["q"]) && empty($trips)): ?>
        <div class="col-12 text-center py-5 text-muted">Aucun trajet trouvé pour cette recherche.</div>
    <?php endif; ?>
</div>
<?php include "footer.php"; ?>
