<?php
session_start();
require 'config/db_connect.php'; 

// SÉCURITÉ : Vérifier que l'utilisateur est bien connecté et est Admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] != 1)) {
    header('Location: connexion.php');
    exit;
}

// TRAITEMENT : Promouvoir un utilisateur
if (isset($_POST['promouvoir_id'])) {
    $id_a_promouvoir = intval($_POST['promouvoir_id']);
    // On met 'admin' (ou 1 selon comment c'est fait dans la BDD)
    $stmt = mysqli_prepare($connexion, "UPDATE utilisateurs SET role_id = 'admin' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_a_promouvoir);
    mysqli_stmt_execute($stmt);
    $message = "Utilisateur promu administrateur avec succès.";
}

// TRAITEMENT : Supprimer un utilisateur
if (isset($_POST['supprimer_id'])) {
    $id_a_supprimer = intval($_POST['supprimer_id']);
    $stmt = mysqli_prepare($connexion, "DELETE FROM utilisateurs WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_a_supprimer);
    mysqli_stmt_execute($stmt);
    $message = "Utilisateur supprimé.";
}

// Récupérer la liste des utilisateurs
$result = mysqli_query($connexion, "SELECT id, nom, email, role_id FROM utilisateurs ORDER BY id DESC");
$utilisateurs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Administration YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">YS - ADMIN</a>
        <div class="d-flex">
            <a href="deconnexion.php" class="btn btn-danger btn-sm">Se déconnecter</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Gestion des Utilisateurs</h2>
    
    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="badge <?= ($user['role_id'] === 'admin' || $user['role_id'] == 1) ? 'bg-danger' : 'bg-primary' ?>">
                                <?= htmlspecialchars($user['role_id']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['role_id'] !== 'admin' && $user['role_id'] != 1): ?>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="promouvoir_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-success">Promouvoir Admin</button>
                            </form>
                            <?php endif; ?>

                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de supprimer ce compte ?');">
                                <input type="hidden" name="supprimer_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>