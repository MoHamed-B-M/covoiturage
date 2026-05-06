<?php
include "header.php";
include "db.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit();
}

// Handle User Deletion
if (isset($_GET["delete_user"])) {
    $id = $_GET["delete_user"];
    try {
        $pdo->beginTransaction();
        
        // 1. Delete bookings for trips created by this user
        $pdo->prepare("DELETE FROM Bookings WHERE trip_id IN (SELECT id FROM Trips WHERE user_id = ?)")->execute([$id]);
        
        // 2. Delete bookings made by this user
        $pdo->prepare("DELETE FROM Bookings WHERE user_id = ?")->execute([$id]);
        
        // 3. Delete trips created by this user
        $pdo->prepare("DELETE FROM Trips WHERE user_id = ?")->execute([$id]);
        
        // 4. Delete the user
        $pdo->prepare("DELETE FROM Users WHERE id = ?")->execute([$id]);
        
        $pdo->commit();
        $success = "Utilisateur et toutes ses données associées ont été supprimés.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erreur lors de la suppression: " . $e->getMessage();
    }
}

// Handle Info Update (Simplified for demo)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_user"])) {
    $id = $_POST["user_id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $stmt = $pdo->prepare("UPDATE Users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $id]);
    $success = "Informations mises à jour.";
}

$users = $pdo->query("SELECT * FROM Users WHERE name != 'admin' ORDER BY id DESC")->fetchAll();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold">Tableau de bord Admin</h1>
            <p class="text-secondary">Gérer les utilisateurs et le système.</p>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="apple-card p-4">
        <h3 class="fw-bold mb-4">Utilisateurs</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user["id"] ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= $user["profile_pic"] ?? "https://ui-avatars.com/api/?name=" . urlencode($user["name"]) ?>" 
                                     class="rounded-circle me-2" style="width:32px; height:32px; object-fit:cover;">
                                <?= htmlspecialchars($user["name"]) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user["email"]) ?></td>
                        <td><?= htmlspecialchars($user["phone"]) ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $user["id"] ?>">
                                Modifier
                            </button>
                            <a href="?delete_user=<?= $user["id"] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $user["id"] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content apple-card p-4">
                                <div class="modal-header border-0 p-0 mb-4">
                                    <h4 class="modal-title fw-bold">Modifier l'utilisateur</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST">
                                    <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user["name"]) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user["email"]) ?>" required>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="update_user" class="btn btn-primary py-3">Enregistrer les modifications</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
