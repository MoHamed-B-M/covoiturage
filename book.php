<?php
session_start();
include "db.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$trip_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT seats FROM Trips WHERE id = ?");
$stmt->execute([$trip_id]);
$trip = $stmt->fetch();

if ($trip && $trip["seats"] > 0) {
    $stmt = $pdo->prepare(
        "INSERT INTO Bookings (user_id, trip_id) VALUES (?, ?)",
    );
    if ($stmt->execute([$user_id, $trip_id])) {
        $pdo->prepare(
            "UPDATE Trips SET seats = seats - 1 WHERE id = ?",
        )->execute([$trip_id]);
        header("Location: my_bookings.php");
    }
} else {
    echo "Désolé, plus de places disponibles.";
}
?>
