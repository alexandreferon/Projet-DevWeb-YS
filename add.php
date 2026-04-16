<?php
require 'config.php';

if ($_POST) {
    $titre = $_POST['titre'];
    $desc = $_POST['description'];
    $img = $_POST['image'];
    $cat = $_POST['categorie'];

    $pdo->prepare("INSERT INTO annonces (titre, description, image, categorie) VALUES (?, ?, ?, ?)")
        ->execute([$titre, $desc, $img, $cat]);

    header("Location: index.php");
}
?>

<form method="POST">
  <input name="titre" placeholder="Titre"><br>
  <input name="description" placeholder="Description"><br>
  <input name="image" placeholder="URL image"><br>
  <input name="categorie" placeholder="Catégorie"><br>
  <button>Ajouter</button>
</form>