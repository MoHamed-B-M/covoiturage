<?php
include "header.php";
include "db.php";
$trips = $pdo
    ->query(
        "SELECT T.*, U.id as user_id, U.name, U.phone, U.profile_pic FROM Trips T JOIN Users U ON T.user_id = U.id WHERE T.seats > 0 ORDER BY T.date_trip ASC",
    )
    ->fetchAll();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="fw-bold display-6">Bonjour, <?= htmlspecialchars(
                $_SESSION["user_name"] ?? "Voyageur",
            ) ?></h1>
            <p class="text-secondary">Trajets disponibles à Sidi Bouzid.</p>
        </div>
        <a href="search.php" class="btn btn-outline-primary rounded-pill px-4">Tout voir</a>
    </div>

    <div class="row g-4">
        <?php foreach ($trips as $t): ?>
        <div class="col-xl-4 col-md-6">
            <!-- apple-card now uses Material 3 expressive rounded corners[cite: 2] -->
            <div class="apple-card h-100 shadow-sm" 
                 role="button"
                 data-trip-id="<?= $t["id"] ?>"
                 data-user-id="<?= $t["user_id"] ?>"
                 data-driver="<?= htmlspecialchars($t["name"]) ?>"
                 data-price="<?= $t["price"] ?>"
                 data-departure="<?= htmlspecialchars($t["departure"]) ?>"
                 data-destination="<?= htmlspecialchars($t["destination"]) ?>"
                 data-time="<?= date("H:i", strtotime($t["date_trip"])) ?>"
                 data-phone="<?= htmlspecialchars($t["phone"]) ?>"
                 data-profile-pic="<?= $t["profile_pic"] ?>">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3 position-relative" style="width:48px; height:48px;">
                            <img src="<?= $t["profile_pic"] ?? "" ?>" alt="Profile" class="rounded-3 shadow-sm user-profile-pic <?= $t["profile_pic"] ? "" : "d-none" ?>" style="width:100%; height:100%; object-fit:cover;">
                            <div class="profile-placeholder bg-primary bg-opacity-10 p-2 rounded-3 d-flex align-items-center justify-content-center h-100 w-100 <?= $t["profile_pic"] ? "d-none" : "" ?>">
                                <i class="bi bi-person-fill text-primary fs-3"></i>
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars(
                                $t["name"],
                            ) ?></div>
                            <div class="small text-muted">Chauffeur</div>
                        </div>
                    </div>
                    <div class="h4 fw-bold text-primary"><?= $t[
                        "price"
                    ] ?> <small class="fs-6">TND</small></div>
                </div>

                <div class="flex-grow-1 mb-4 position-relative ps-4">
                    <!-- Visual trip line connector[cite: 2] -->
                    <div class="position-absolute start-0 h-100 border-start border-2 border-primary border-opacity-25 ms-1" style="left: 6px !important; top: 10px; height: calc(100% - 25px) !important;"></div>

                    <div class="mb-3 position-relative">
                        <i class="bi bi-circle-fill text-primary position-absolute" style="left:-22px; font-size:10px; top:6px;"></i>
                        <div class="small text-muted">Départ</div>
                        <div class="fw-semibold"><?= htmlspecialchars(
                            $t["departure"],
                        ) ?></div>
                    </div>

                    <div class="position-relative">
                        <i class="bi bi-geo-alt-fill text-danger position-absolute" style="left:-24px; top:4px;"></i>
                        <div class="small text-muted">Destination</div>
                        <div class="fw-semibold"><?= htmlspecialchars(
                            $t["destination"],
                        ) ?></div>
                    </div>
                </div>

                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-secondary small"><?= date(
                        "H:i",
                        strtotime($t["date_trip"]),
                    ) ?></span>
                    <a href="book.php?id=<?= $t[
                        "id"
                    ] ?>" class="btn btn-primary rounded-pill px-4 fw-bold">Réserver</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include "footer.php"; ?>
