<?php
session_start();
include "db.php";
if (!isset($_SESSION["user_id"])) {
    exit();
}

$booking_id = $_GET["id"];

// Get trip ID before cancelling
$stmt = $pdo->prepare(
    "SELECT trip_id FROM Bookings WHERE id = ? AND user_id = ?",
);
$stmt->execute([$booking_id, $_SESSION["user_id"]]);
$booking = $stmt->fetch();

if ($booking) {
    // Mark as cancelled and return seat
    $pdo->prepare(
        "UPDATE Bookings SET status = 'cancelled' WHERE id = ?",
    )->execute([$booking_id]);
    $pdo->prepare("UPDATE Trips SET seats = seats + 1 WHERE id = ?")->execute([
        $booking["trip_id"],
    ]);
}
header("Location: my_bookings.php");
?>
