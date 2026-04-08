<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $db   = 'ys_database';
    $user = 'root';
    $pass = 'root';
    $pdo  = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    $stmt = $pdo->prepare("
        INSERT INTO annonces (user_id, titre, prix, etat, description, image_url)
        VALUES (:user_id, :titre, :prix, :etat, :description, :image_url)
    ");
    $stmt->execute([
        ':user_id'     => $user_id,
        ':titre'       => $titre,
        ':prix'        => $prix,
        ':etat'        => $etat,
        ':description' => $description,
        ':image_url'   => $image_url,
    ]);

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
<body>

<form action="annonce.php" method="POST" enctype="multipart/form-data">

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