<?php
session_start();
include('config/db_connect.php');

// Sécurité : Etre connecté
if (!isset($_SESSION['user_id'])) { 
    header('Location: connexion.php'); 
    exit; 
}

$mon_id = $_SESSION['user_id'];
// On récupère l'ID du vendeur à qui on veut parler
$id_autre_user = isset($_GET['id_vendeur']) ? intval($_GET['id_vendeur']) : 0;

if ($id_autre_user === 0) {
    die("Erreur : Aucun destinataire spécifié.");
}

// 1. TRAITEMENT : Envoi d'un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['message']))) {
    $contenu = trim($_POST['message']);
    
    // CORRECTION : On retire 'id_annonce' car la colonne n'existe pas dans votre SQL
    $stmt = mysqli_prepare($connexion, "INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iis", $mon_id, $id_autre_user, $contenu);
    mysqli_stmt_execute($stmt);
    
    header("Location: discussion.php?id_vendeur=$id_autre_user");
    exit;
}

// 2. RÉCUPÉRATION DES MESSAGES
// On récupère tous les messages entre moi et l'autre utilisateur
$query = "SELECT * FROM messages 
          WHERE (expediteur_id = ? AND destinataire_id = ?) 
          OR (expediteur_id = ? AND destinataire_id = ?)
          ORDER BY date_envoi ASC";

$stmt = mysqli_prepare($connexion, $query);
mysqli_stmt_bind_param($stmt, "iiii", $mon_id, $id_autre_user, $id_autre_user, $mon_id);
mysqli_stmt_execute($stmt);
$resultat = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chat - YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-box { height: 400px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 10px; }
        .msg { margin-bottom: 10px; padding: 8px 15px; border-radius: 15px; display: inline-block; max-width: 80%; }
        .msg-me { background: #007bff; color: white; float: right; clear: both; }
        .msg-them { background: #e9ecef; color: black; float: left; clear: both; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <span>Discussion</span>
                    <a href="favoris.php" class="btn btn-sm btn-outline-light">Retour</a>
                </div>
                <div class="card-body">
                    <div class="chat-box mb-3">
                        <?php while ($msg = mysqli_fetch_assoc($resultat)): ?>
                            <div class="msg <?= $msg['expediteur_id'] == $mon_id ? 'msg-me' : 'msg-them' ?>">
                                <?= nl2br(htmlspecialchars($msg['contenu'])) ?>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <form method="POST">
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Votre message..." required>
                            <button class="btn btn-primary" type="submit">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>