<?php
require 'config/db_connect.php'; // Chemin mis à jour

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] // Exemple basé sur votre SQL. À remplacer par $_SESSION['user_id'] plus tard.
    $titre = $_POST['titre'];
    $desc = $_POST['description'];
    $img = $_POST['image_url'];
    $prix = $_POST['prix'];
    $etat = $_POST['etat'];

    $query = "INSERT INTO annonces (user_id, titre, description, image_url, prix, etat) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connexion, $query);
    mysqli_stmt_bind_param($stmt, "isssds", $user_id, $titre, $desc, $img, $prix, $etat);
    mysqli_stmt_execute($stmt);

    header("Location: recherche.php");
    exit;
}
?>

<form method="POST">
  <input name="titre" placeholder="Titre" required><br>
  <textarea name="description" placeholder="Description"></textarea><br>
  <input name="image_url" placeholder="URL image"><br>
  <input type="number" name="prix" placeholder="Prix" step="0.01"><br>
  <select name="etat">
      <option value="neuf">Neuf</option>
      <option value="bon état">Bon état</option>
      <option value="correct">Correct</option>
  </select><br>
  <button type="submit">Ajouter</button>
</form>