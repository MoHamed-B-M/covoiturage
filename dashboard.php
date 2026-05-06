<?php include "header.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
}
?>
<div class="text-center py-5">
    <h1 class="display-4 fw-bold">Bonjour, <?= htmlspecialchars(
        $_SESSION["user_name"],
    ) ?></h1>
    <div class="row justify-content-center mt-5 g-4">
        <div class="col-md-3">
            <a href="search.php" class="text-decoration-none">
                <div class="apple-card p-4">
                    <h3 class="mb-3">🔍</h3>
                    <h5 class="fw-bold text-dark">Trouver un trajet</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="add_trip.php" class="text-decoration-none">
                <div class="apple-card p-4">
                    <h3 class="mb-3">🚗</h3>
                    <h5 class="fw-bold text-dark">Publier un trajet</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="my_bookings.php" class="text-decoration-none">
                <div class="apple-card p-4">
                    <h3 class="mb-3">📅</h3>
                    <h5 class="fw-bold text-dark">Mes réservations</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="my_trips.php" class="text-decoration-none">
                <div class="apple-card p-4">
                    <h3 class="mb-3">👤</h3>
                    <h5 class="fw-bold text-dark">Mes annonces</h5>
                </div>
            </a>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
