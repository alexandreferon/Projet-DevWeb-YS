<?php
session_start();
include('config/db_connect.php');

if (!isset($_SESSION['user_id'])) { header('Location: connexion.php'); exit; }

$user_id = $_SESSION['user_id'];
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

// On récupère les favoris
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
    <title>Mes Favoris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top { height: 180px; object-fit: cover; }
        .card { border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>Mes Favoris ❤️</h2>
        <a href="index.php" class="btn btn-secondary">Retour</a>
    </div>

    <div class="row">
        <?php while ($annonce = mysqli_fetch_assoc($resultat)): ?>
            <div class="col-md-4 mb-4"> <div class="card h-100">
                    <?php 
                        $img = !empty($annonce['image_url']) ? $base . $annonce['image_url'] : 'https://via.placeholder.com/300x200';
                    ?>
                    <img src="<?= $img ?>" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                        <p class="fw-bold text-primary"><?= number_format($annonce['prix'], 2) ?> €</p>
                        
                          <div class="d-grid gap-2">
                              <a href="discussion.php?id_annonce=<?= $annonce['id'] ?>&id_vendeur=<?= $annonce['user_id'] ?>" class="btn btn-primary">
                                  💬 Chat avec le vendeur
                              </a>
                              <a href="delete_fav.php?id=<?= $annonce['id'] ?>" class="btn btn-sm btn-outline-danger">Supprimer</a>
                          </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>