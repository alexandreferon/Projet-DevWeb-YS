<?php
// 1. Démarrer la session (Toujours en premier)
session_start();

// 2. LE VIGILE : Vérifier si l'utilisateur est connecté
// S'il n'y a pas d'ID utilisateur dans la session, on le vire vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit(); // On bloque le reste de la page
}

// 3. (Bonus) On inclut la base de données pour quand l'équipe voudra faire le code d'insertion
include('config/db_connect.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une annonce - YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="alert alert-info">
        Connecté en tant que : <strong><?php echo $_SESSION['user_nom']; ?></strong>
        <a href="index.php" class="float-end">Retour à l'accueil</a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h3 class="text-center">Déposer une nouvelle annonce</h3>
        </div>
        <div class="card-body text-center">
            <p>Ici, l'équipe pourra ajouter le formulaire complet (Titre, Prix, Description, Image...).</p>
            <p><strong>L'essentiel est là : cette page est protégée, aucun visiteur anonyme ne peut la voir !</strong></p>
        </div>
    </div>
</div>

</body>
</html>