<?php
session_start(); 
include('config/db_connect.php');

$message = "";

if (isset($_POST['valider_connexion'])) {
    
    $email = $_POST['email'];
    $password_saisi = $_POST['password'];

    // REQUÊTE PRÉPARÉE POUR LA CONNEXION
    $stmt = mysqli_prepare($connexion, "SELECT id, nom, password, role_id, avatar FROM utilisateurs WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    
    $resultat = mysqli_stmt_get_result($stmt);

    if ($utilisateur = mysqli_fetch_assoc($resultat)) {
        if (password_verify($password_saisi, $utilisateur['password'])) {
            
            // On sauvegarde toutes les infos utiles dans la session
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['user_nom'] = $utilisateur['nom'];
            $_SESSION['user_role'] = $utilisateur['role_id'];
            $_SESSION['user_avatar'] = $utilisateur['avatar'];
            
            header("Location: index.php");
            exit(); 
        } else {
            $message = "<div class='alert alert-danger'>Mot de passe incorrect.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Aucun compte trouvé avec cette adresse email.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - YS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; min-height: 100vh; display: flex; align-items: center; }
        .ys-logo { background-color: #212529; color: #ffffff; padding: 12px 18px; border-radius: 12px; font-size: 24px; font-weight: 900; display: inline-block; }
        .ys-titre { font-family: Georgia, serif; font-weight: bold; color: #212529; }
        .ys-btn { background-color: #212529; color: white; border: none; font-weight: 500; }
        .ys-btn:hover { background-color: #424649; color: white; }
        .ys-link { color: #0d6efd; text-decoration: none; }
        .ys-link:hover { text-decoration: underline; }
        .form-control { border-radius: 8px; padding: 10px 15px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <div class="text-center mb-4">
                    <div class="ys-logo mb-3">YS</div>
                    <h2 class="ys-titre mb-2">Connexion</h2>
                    <p class="text-muted" style="font-size: 0.95rem;">Connectez-vous à votre compte</p>
                </div>
                <?php echo $message; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label text-dark" style="font-weight: 500;">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-dark" style="font-weight: 500;">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="valider_connexion" class="btn ys-btn w-100 py-2 fs-6 rounded-3">Se connecter</button>
                </form>
                <div class="mt-4 text-center" style="font-size: 0.95rem;">
                    <span class="text-muted">Pas encore de compte ?</span> 
                    <a href="inscription.php" class="ys-link">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>