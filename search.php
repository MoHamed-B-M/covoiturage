<?php include "header.php";
include "db.php";
$trips = [];
if (isset($_GET["q"])) {
    $q = "%" . $_GET["q"] . "%";
    $stmt = $pdo->prepare(
        "SELECT T.*, U.id as user_id, U.name, U.phone, U.profile_pic FROM Trips T JOIN Users U ON T.user_id = U.id WHERE (T.departure LIKE ? OR T.destination LIKE ?) AND T.seats > 0",
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
        <div class="apple-card p-4" 
             role="button"
             data-trip-id="<?= $t["id"] ?>"
             data-user-id="<?= $t["user_id"] ?>"
             data-driver="<?= htmlspecialchars($t["name"]) ?>"
             data-price="<?= $t["price"] ?>"
             data-departure="<?= htmlspecialchars($t["departure"]) ?>"
             data-destination="<?= htmlspecialchars($t["destination"]) ?>"
             data-time="<?= date("H:i", strtotime($t["date_trip"])) ?>"
             data-phone="<?= htmlspecialchars($t["phone"]) ?>"
             data-profile-pic="<?= $t["profile_pic"] ?>"
             data-car="<?= htmlspecialchars($t["car_brand"] ?? "Véhicule non spécifié") ?>"
             data-offers="<?= htmlspecialchars($t["offers"] ?? "Aucun service additionnel") ?>">
            
            <div class="d-flex align-items-center mb-3">
                <div class="me-2 position-relative" style="width:32px; height:32px;">
                    <img src="<?= $t["profile_pic"] ?? "" ?>" alt="Profile" class="rounded-circle user-profile-pic <?= $t["profile_pic"] ? "" : "d-none" ?>" style="width:100%; height:100%; object-fit:cover;">
                    <div class="profile-placeholder bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center h-100 w-100 <?= $t["profile_pic"] ? "d-none" : "" ?>">
                        <i class="bi bi-person-fill text-primary small"></i>
                    </div>
                </div>
                <div class="small fw-bold text-muted"><?= htmlspecialchars($t["name"]) ?></div>
            </div>

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
