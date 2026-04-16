<?php
require 'config.php';

$favoris = $pdo->query("
SELECT annonces.*, favoris.annonce_id
FROM annonces
JOIN favoris ON annonces.id = favoris.annonce_id
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Favoris</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<a href="index.php" class="btn btn-secondary mb-3">⬅ Retour</a>

<h1 class="mb-4">Mes favoris ❤️</h1>

<div class="row">

<?php foreach($favoris as $f): ?>

  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-body">
        
        <h5 class="card-title"><?= $f['titre'] ?></h5>
        
        <p class="card-text"><?= $f['description'] ?></p>

        <a href="delete_fav.php?id=<?= $f['annonce_id'] ?>" class="btn btn-danger">
          ❌ Supprimer
        </a>

      </div>
    </div>
  </div>

<?php endforeach; ?>

</div>

</body>
</html>
