<?php
session_start();
include('config/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Jointure pour récupérer les détails des annonces mises en favoris
$query = "SELECT a.* FROM annonces a 
          JOIN favoris f ON a.id = f.annonce_id 
          WHERE f.utilisateur_id = ?";
$stmt = mysqli_prepare($connexion, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$resultat = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Favoris - YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .ys-navbar { background-color: #212529; }
        .card { border-radius: 12px; overflow: hidden; }
        .btn-delete { color: #dc3545; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark ys-navbar shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">YS</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
            <a href="index.php" class="btn btn-outline-light btn-sm">Accueil</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Mes favoris ❤️</h2>
    <div class="row">
        <?php while ($annonce = mysqli_fetch_assoc($resultat)): ?>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <?php if ($annonce['image_url']): ?>
                        <img src="<?= $annonce['image_url'] ?>" class="card-img-top" alt="Annonce" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                        <p class="card-text text-muted small"><?= htmlspecialchars(substr($annonce['description'], 0, 100)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark"><?= number_format($annonce['prix'], 2) ?> €</span>
                            <a href="delete_fav.php?id=<?= $annonce['id'] ?>" class="btn-delete">Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>