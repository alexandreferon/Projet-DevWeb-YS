<?php
session_start();
include('config/db_connect.php');

if (!isset($_SESSION['user_id'])) { header('Location: connexion.php'); exit; }

$mon_id = $_SESSION['user_id'];
$id_annonce = isset($_GET['id_annonce']) ? intval($_GET['id_annonce']) : 0;
$id_autre_user = isset($_GET['id_vendeur']) ? intval($_GET['id_vendeur']) : 0;

// On vérifie qu'on a bien les infos nécessaires
if ($id_annonce == 0 || $id_autre_user == 0) {
    die("Erreur : Impossible d'identifier la discussion pour cette annonce.");
}

// 1. Envoyer un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['message']))) {
    $msg = trim($_POST['message']);
    
    // On insère avec id_annonce (la colonne que tu viens de créer)
    $stmt = mysqli_prepare($connexion, "INSERT INTO messages (id_annonce, expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiis", $id_annonce, $mon_id, $id_autre_user, $msg);
    mysqli_stmt_execute($stmt);
    
    header("Location: discussion.php?id_annonce=$id_annonce&id_vendeur=$id_autre_user");
    exit;
}

// 2. Récupérer les messages liés à CETTE annonce uniquement
$query = "SELECT * FROM messages 
          WHERE id_annonce = ? 
          AND ((expediteur_id = ? AND destinataire_id = ?) OR (expediteur_id = ? AND destinataire_id = ?))
          ORDER BY date_envoi ASC";

$stmt = mysqli_prepare($connexion, $query);
mysqli_stmt_bind_param($stmt, "iiiii", $id_annonce, $mon_id, $id_autre_user, $id_autre_user, $mon_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chat - YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">Discussion - Annonce #<?= $id_annonce ?></div>
            <div class="card-body" style="height: 400px; overflow-y: auto;">
                <?php while ($m = mysqli_fetch_assoc($res)): ?>
                    <div class="mb-2 <?= $m['expediteur_id'] == $mon_id ? 'text-end' : 'text-start' ?>">
                        <div class="d-inline-block p-2 rounded <?= $m['expediteur_id'] == $mon_id ? 'bg-primary text-white' : 'bg-white border' ?>">
                            <?= htmlspecialchars($m['contenu']) ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="card-footer bg-white">
                <form method="POST">
                    <div class="input-group">
                        <input type="text" name="message" class="form-control" placeholder="Ecrivez votre message..." required>
                        <button class="btn btn-primary">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="favoris.php" class="text-muted">Retour aux favoris</a>
        </div>
    </div>
</body>
</html>