<?php
session_start();
require 'config/db_connect.php';

// Sécurité : Etre connecté pour parler
if (!isset($_SESSION['user_id'])) { 
    header('Location: connexion.php'); 
    exit; 
}

$id_annonce = intval($_GET['id_annonce'] ?? 0);
$id_autre_user = intval($_GET['id_vendeur'] ?? 0); 
$mon_id = $_SESSION['user_id'];

// TRAITEMENT : Envoi d'un message
if (isset($_POST['message']) && !empty(trim($_POST['message']))) {
    $contenu = trim($_POST['message']);
    
    $stmt = mysqli_prepare($connexion, "INSERT INTO messages (id_annonce, id_expediteur, id_destinataire, contenu) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiis", $id_annonce, $mon_id, $id_autre_user, $contenu);
    mysqli_stmt_execute($stmt);
    
    // On recharge la page pour vider le formulaire
    header("Location: discussion.php?id_annonce=$id_annonce&id_vendeur=$id_autre_user");
    exit;
}

// Récupérer la discussion (1 fil = 1 annonce + 2 utilisateurs)
$stmt = mysqli_prepare($connexion, "
    SELECT * FROM messages 
    WHERE id_annonce = ? 
    AND ((id_expediteur = ? AND id_destinataire = ?) OR (id_expediteur = ? AND id_destinataire = ?))
    ORDER BY date_envoi ASC
");
mysqli_stmt_bind_param($stmt, "iiiii", $id_annonce, $mon_id, $id_autre_user, $id_autre_user, $mon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Messagerie - YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">YS</a>
        <div class="d-flex">
            <a href="javascript:history.back()" class="btn btn-outline-light btn-sm">Retour</a>
        </div>
    </div>
</nav>

<div class="container mt-4" style="max-width: 800px;">
    <h3 class="mb-3">Discussion en cours</h3>
    
    <div class="card p-4 mb-3 shadow-sm border-0" style="height: 500px; overflow-y: auto; background-color: white;">
        <?php if (empty($messages)): ?>
            <p class="text-center text-muted mt-5">Aucun message. Envoyez le premier message !</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="<?= $msg['id_expediteur'] == $mon_id ? 'text-end' : 'text-start' ?> mb-3">
                    <div class="d-inline-block p-2 px-3 rounded-3 <?= $msg['id_expediteur'] == $mon_id ? 'bg-primary text-white' : 'bg-secondary text-white' ?>" style="max-width: 75%;">
                        <?= nl2br(htmlspecialchars($msg['contenu'])) ?>
                    </div>
                    <small class="d-block text-muted mt-1" style="font-size: 0.75rem;"><?= htmlspecialchars($msg['date_envoi']) ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form method="POST">
        <div class="input-group shadow-sm">
            <input type="text" name="message" class="form-control p-3" placeholder="Tapez votre message ici..." required>
            <button type="submit" class="btn btn-success px-4 fw-bold">Envoyer</button>
        </div>
    </form>
</div>

</body>
</html>