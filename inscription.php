<?php
include('config/db_connect.php');

$message = "";

if (isset($_POST['valider_inscription'])) {
    
    // Plus besoin de mysqli_real_escape_string avec les requêtes préparées !
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password_brut = $_POST['password'];

    // 1. REQUÊTE PRÉPARÉE POUR LA VÉRIFICATION
    $stmt_verif = mysqli_prepare($connexion, "SELECT id FROM utilisateurs WHERE email = ?");
    mysqli_stmt_bind_param($stmt_verif, "s", $email); // "s" veut dire que c'est un String (texte)
    mysqli_stmt_execute($stmt_verif);
    mysqli_stmt_store_result($stmt_verif);

    if (mysqli_stmt_num_rows($stmt_verif) > 0) {
        $message = "<div class='alert alert-warning'>Cette adresse email est déjà utilisée. Veuillez en choisir une autre ou vous connecter.</div>";
    } else {
        $password_hache = password_hash($password_brut, PASSWORD_DEFAULT);
        
        // 2. REQUÊTE PRÉPARÉE POUR L'INSERTION
        // Note : le role_id (1) et l'avatar par défaut seront ajoutés automatiquement grâce à ton nouveau code SQL !
        $stmt_insert = mysqli_prepare($connexion, "INSERT INTO utilisateurs (nom, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "sss", $nom, $email, $password_hache);

        if (mysqli_stmt_execute($stmt_insert)) {
            $message = "<div class='alert alert-success'>Inscription réussie ! Vous pouvez maintenant vous connecter.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Une erreur est survenue lors de l'enregistrement.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - YS</title>
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
                    <h2 class="ys-titre mb-2">Créer un compte</h2>
                    <p class="text-muted" style="font-size: 0.95rem;">Rejoignez la plateforme YS</p>
                </div>
                <?php echo $message; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label text-dark" style="font-weight: 500;">Nom complet</label>
                        <input type="text" name="nom" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark" style="font-weight: 500;">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-dark" style="font-weight: 500;">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="valider_inscription" class="btn ys-btn w-100 py-2 fs-6 rounded-3">S'inscrire</button>
                </form>
                <div class="mt-4 text-center" style="font-size: 0.95rem;">
                    <span class="text-muted">Déjà un compte ?</span> 
                    <a href="connexion.php" class="ys-link">Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>