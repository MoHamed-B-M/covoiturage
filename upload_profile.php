<?php
session_start();
include "db.php";

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Non autorisé"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_pic"])) {
    $user_id = $_SESSION["user_id"];
    $target_dir = "uploads/";
    
    // Create directory if not exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
    $allowed_extensions = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(["success" => false, "message" => "Format non supporté"]);
        exit();
    }

    $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        // Update database
        $stmt = $pdo->prepare("UPDATE Users SET profile_pic = ? WHERE id = ?");
        $stmt->execute([$target_file, $user_id]);

        echo json_encode([
            "success" => true, 
            "url" => $target_file,
            "message" => "Image mise à jour avec succès"
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors du déplacement du fichier"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Requête invalide"]);
}
?>
