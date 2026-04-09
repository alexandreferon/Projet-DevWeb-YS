<?php
session_start();

// Le fameux vigile : on protège la page
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// On définit le nom du rôle pour l'affichage (1 = Membre, 2 = Admin)
$role_affichage = ($_SESSION['user_role'] == 2) ? "Administrateur" : "Membre";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .ys-navbar { background-color: #212529; }
        .avatar-cercle { 
            width: 50px; height: 50px; 
            background-color: #0d6efd; color: white; 
            border-radius: 50%; display: flex; 
            align-items: center; justify-content: center; 
            font-weight: bold; font-size: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark ys-navbar shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">YS</a>
        <div class="d-flex align-items-center">
            <a href="mes_annonces.php" class="btn btn-outline-light btn-sm me-3">Publier une annonce</a>
            <a href="deconnexion.php" class="btn btn-danger btn-sm">Se déconnecter</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar-cercle me-3">
                        <?php echo strtoupper(substr($_SESSION['user_nom'], 0, 1)); ?>
                    </div>
                    <div>
                        <h3 class="mb-0">Bonjour, <?php echo htmlspecialchars($_SESSION['user_nom']); ?> !</h3>
                        <span class="badge bg-secondary">Rôle : <?php echo $role_affichage; ?></span>
                    </div>
                </div>

                <div class="alert alert-success">
                    <h5 class="alert-heading"> Ma Mission de Yasser Jinani Validée ! </h5>
                    <p class="mb-0">Votre système d'authentification est sécurisé (Hachage, Sessions, Requêtes préparées). La base de données relationnelle est en place.</p>
                </div>
                
                <p class="text-muted mt-4">C'est ici que l'équipe pourra intégrer le design final et la liste des annonces , à vous de terminer le projet MERCI!</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>