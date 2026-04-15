<?php
session_start();
include('config/db_connect.php');

// On récupère l'ID de l'annonce dans l'URL (ex: detail_annonce.php?id=1)
$id_annonce = intval($_GET['id'] ?? 0);

$stmt = mysqli_prepare($connexion, "SELECT * FROM annonces WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id_annonce);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$annonce = mysqli_fetch_assoc($result);

if (!$annonce) {
    die("Annonce introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($annonce['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h1><?= htmlspecialchars($annonce['titre']) ?></h1>
    <p class="text-muted">Prix : <?= $annonce['prix'] ?> € | État : <?= $annonce['etat'] ?></p>
    
    <?php if ($annonce['image_url']): ?>
        <img src="<?= htmlspecialchars($annonce['image_url']) ?>" style="max-width: 300px; border-radius: 8px;">
    <?php endif; ?>
    
    <p class="mt-3"><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>

    <hr>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $annonce['user_id']): ?>
        <a href="discussion.php?id_annonce=<?= $annonce['id'] ?>&id_vendeur=<?= $annonce['user_id'] ?>" class="btn btn-primary">
            ✉️ Contacter le vendeur
        </a>
    <?php elseif (!isset($_SESSION['user_id'])): ?>
        <p><a href="connexion.php">Connectez-vous</a> pour envoyer un message.</p>
    <?php else: ?>
        <p class="text-muted">Ceci est votre annonce.</p>
    <?php endif; ?>

</body>
</html>