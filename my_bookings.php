<?php
// 1. Logic and Data Fetching FIRST
include "db.php";
include "header.php"; // This now handles session_start() safely

// Initialize $bookings as an empty array to prevent the "Undefined" warning
$bookings = [];

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];

    // Fetch bookings for the logged-in user
    $stmt = $pdo->prepare("
        SELECT B.id as booking_id, B.status, T.*
        FROM Bookings B
        JOIN Trips T ON B.trip_id = T.id
        WHERE B.user_id = ?
    ");
    $stmt->execute([$userId]);
    $bookings = $stmt->fetchAll(); // Assigns the array of results to $bookings[cite: 3]
}
?>

<!-- 2. Your HTML UI -->
<div class="container mt-5">
    <h2 class="fw-bold mb-4">Mes réservations</h2>

    <div class="apple-card">
        <table class="table mb-0">
            <thead>
                <tr class="text-secondary small">
                    <th>TRAJET</th>
                    <th>DATE</th>
                    <th>PRIX</th>
                    <th class="text-end">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td class="py-3 fw-medium">
                            <?= htmlspecialchars(
                                $b["departure"],
                            ) ?> → <?= htmlspecialchars($b["destination"]) ?>
                        </td>
                        <td class="py-3 text-muted">
                            <?= date("d/m/Y H:i", strtotime($b["date_trip"])) ?>
                        </td>
                        <td class="py-3 fw-bold"><?= $b["price"] ?> TND</td>
                        <td class="text-end">
                            <?php if (
                                isset($b["status"]) &&
                                $b["status"] == "active"
                            ): ?>
                                <a href="cancel_booking.php?id=<?= $b[
                                    "booking_id"
                                ] ?>" class="btn btn-sm btn-outline-danger btn-apple">Annuler</a>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill">Annulé</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            Vous n'avez aucune réservation pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "footer.php"; ?>
