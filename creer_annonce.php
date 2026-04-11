<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

include('config/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre       = trim($_POST['titre']);
    $prix        = floatval($_POST['prix']);
    $etat        = $_POST['etat'];
    $description = trim($_POST['description']);
    $user_id     = $_SESSION['user_id'];

    $image_url = null;
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = 'uploads/annonces/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext     = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array(strtolower($ext), $allowed)) {
            die('Type de fichier non autorisé.');
        }

        $filename = uniqid('img_') . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
        $image_url = $upload_dir . $filename;
    }

    $stmt = mysqli_prepare($connexion, "INSERT INTO annonces (user_id, titre, prix, etat, description, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isdsss", $user_id, $titre, $prix, $etat, $description, $image_url);
    mysqli_stmt_execute($stmt);

    header('Location: mes_annonces.php?success=1');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Déposer une annonce</title>
</head>
<nav class="navbar navbar-expand-lg navbar-dark ys-navbar shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">YS</a>
        <div class="d-flex align-items-center">
            <a href="mes_annonces.php" class="btn btn-outline-light btn-sm me-3">Publier une annonce</a>
            <a href="deconnexion.php" class="btn btn-danger btn-sm">Se déconnecter</a>
        </div>
    </div>
</nav>
<body>

<form action="creer_annonce.php" method="POST" enctype="multipart/form-data">

    <label for="titre">Titre</label>
    <input required type="text" name="titre" id="titre" placeholder="Nom de l'article">

    <label for="prix">Prix (€)</label>
    <input required type="number" name="prix" id="prix" min="0" step="0.01" placeholder="Ex: 19.99">

    <label for="etat">État</label>
    <select name="etat" id="etat">
        <option value="neuf">Neuf</option>
        <option value="bon état">Bon état</option>
        <option value="correct">Correct</option>
    </select>

    <label for="description">Description</label>
    <textarea required name="description" id="description" placeholder="Décrivez votre article..."></textarea>

    <label for="image">Photo</label>
    <input type="file" name="image" id="image" accept="image/*">

    <button type="submit">Publier l'annonce</button>

</form>

</body>
</html>