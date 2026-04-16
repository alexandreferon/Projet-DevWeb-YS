<?php
session_start();
include('config/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$mon_id = $_SESSION['user_id'];

// Cette requête récupère la liste des personnes avec qui vous avez discuté
// Elle sélectionne le dernier message de chaque conversation par annonce
$query = "
    SELECT m.*, a.titre as titre_annonce, u.nom as nom_interlocuteur,
    CASE WHEN m.id_expediteur = ? THEN m.id_destinataire ELSE m.id_expediteur END as interlocuteur_id
    FROM messages m
    JOIN annonces a ON m.id_annonce = a.id
    JOIN utilisateurs u ON u.id = (CASE WHEN m.id_expediteur = ? THEN m.id_destinataire ELSE m.id_expediteur END)
    WHERE m.id_expediteur = ? OR m.id_destinataire = ?
    GROUP BY m.id_annonce, interlocuteur_id
    ORDER BY m.date_envoi DESC";

$stmt = mysqli_prepare($connexion, $query);
mysqli_stmt_bind_param($stmt, "iiii", $mon_id, $mon_id, $mon_id, $mon_id);
mysqli_stmt_execute($stmt);
$resultat = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Messagerie - YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">YS</a>
        <a href="index.php" class="btn btn-outline-light btn-sm">Accueil</a>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Mes Conversations ✉️</h2>
    
    <div class="list-group shadow-sm">
        <?php while ($conv = mysqli_fetch_assoc($resultat)): ?>
            <a href="discussion.php?id_annonce=<?= $conv['id_annonce'] ?>&id_vendeur=<?= $conv['interlocuteur_id'] ?>" 
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                <div>
                    <h6 class="mb-1">Annonce : <strong><?= htmlspecialchars($conv['titre_annonce']) ?></strong></h6>
                    <p class="mb-0 text-muted small">Avec : <?= htmlspecialchars($conv['nom_interlocuteur']) ?></p>
                    <p class="mb-0 text-truncate" style="max-width: 300px;"><?= htmlspecialchars($conv['contenu']) ?></p>
                </div>
                <span class="badge bg-primary rounded-pill">Voir</span>
            </a>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($resultat) == 0): ?>
            <div class="alert alert-info">Vous n'avez pas encore de messages.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>