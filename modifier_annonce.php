<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

include('config/db_connect.php');

$id = intval($_GET['id'] ?? 0);

// Récupérer l'annonce (en vérifiant que ça appartient bien à l'utilisateur connecté)
$stmt = mysqli_prepare($connexion, "SELECT * FROM annonces WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$annonce = mysqli_fetch_assoc($result);

if (!$annonce) {
    header('Location: mes_annonces.php');
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = trim($_POST['titre']);
    $prix        = floatval($_POST['prix']);
    $etat        = $_POST['etat'];
    $description = trim($_POST['description']);

    $image_url = $annonce['image_url']; // on garde l'ancienne image par défaut

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

    $stmt_update = mysqli_prepare($connexion, "UPDATE annonces SET titre = ?, prix = ?, etat = ?, description = ?, image_url = ? WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt_update, "sdsssis", $titre, $prix, $etat, $description, $image_url, $id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt_update);

    header('Location: mes_annonces.php?updated=1');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Modifier l'annonce</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 2rem auto; padding: 0 1rem; }
        h1 { font-size: 22px; font-weight: 500; margin-bottom: 1.5rem; }
        label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px; margin-top: 12px; }
        input, select, textarea { width: 100%; padding: 8px 12px; border: 0.5px solid #ccc; border-radius: 8px; font-size: 14px; box-sizing: border-box; }
        textarea { height: 100px; resize: vertical; }
        .btn-submit { margin-top: 16px; padding: 10px 20px; background: #212529; color: white; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; }
        .btn-submit:hover { background: #424649; }
        .btn-annuler { margin-top: 8px; display: inline-block; padding: 10px 20px; border: 0.5px solid #ccc; border-radius: 8px; text-decoration: none; color: #333; font-size: 14px; }
        .btn-annuler:hover { background: #f5f5f5; }
        .image-actuelle { margin-top: 8px; font-size: 12px; color: #888; }
        .image-actuelle img { display: block; margin-top: 6px; width: 120px; height: 80px; object-fit: cover; border-radius: 8px; }
    </style>
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

<h1>Modifier l'annonce</h1>

<form action="modifier_annonce.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">

    <label for="titre">Titre</label>
    <input required type="text" name="titre" id="titre" value="<?= htmlspecialchars($annonce['titre']) ?>">

    <label for="prix">Prix (€)</label>
    <input required type="number" name="prix" id="prix" min="0" step="0.01" value="<?= $annonce['prix'] ?>">

    <label for="etat">État</label>
    <select name="etat" id="etat">
        <option value="neuf" <?= $annonce['etat'] === 'neuf' ? 'selected' : '' ?>>Neuf</option>
        <option value="bon état" <?= $annonce['etat'] === 'bon état' ? 'selected' : '' ?>>Bon état</option>
        <option value="correct" <?= $annonce['etat'] === 'correct' ? 'selected' : '' ?>>Correct</option>
    </select>

    <label for="description">Description</label>
    <textarea required name="description" id="description"><?= htmlspecialchars($annonce['description']) ?></textarea>

    <label for="image">Nouvelle photo (optionnel)</label>
    <?php if ($annonce['image_url']): ?>
        <div class="image-actuelle">
            Image actuelle :
            <img src="<?= htmlspecialchars($annonce['image_url']) ?>" alt="image actuelle">
        </div>
    <?php endif; ?>
    <input type="file" name="image" id="image" accept="image/*" style="margin-top: 8px;">

    <button type="submit" class="btn-submit">💾 Enregistrer les modifications</button>
    <a href="mes_annonces.php" class="btn-annuler">Annuler</a>

</form>

</body>
</html>
