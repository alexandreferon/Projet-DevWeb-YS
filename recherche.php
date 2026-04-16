
<?php
session_start();
require 'config/db_connect.php';

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

// COMPTEUR FAVORIS
$count_result = mysqli_query($connexion, "SELECT COUNT(*) FROM favoris");
$count = mysqli_fetch_row($count_result)[0];

// RECHERCHE
if (isset($_GET['q']) && $_GET['q'] != "") {
    $q = "%" . $_GET['q'] . "%";
    $stmt = mysqli_prepare($connexion, "SELECT * FROM annonces WHERE titre LIKE ?");
    mysqli_stmt_bind_param($stmt, "s", $q);
    mysqli_stmt_execute($stmt);
    $annonces = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
} else {
    $result = mysqli_query($connexion, "SELECT * FROM annonces ORDER BY id DESC");
    $annonces = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>YS Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(120deg, #f6f7fb, #e9ecf5); font-family: 'Segoe UI', sans-serif; }
    .hero { background: linear-gradient(135deg, #000, #2c2c2c); color: white; padding: 70px; border-radius: 20px; margin-bottom: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
    .card { border: none; border-radius: 20px; overflow: hidden; transition: 0.4s; animation: fadeInUp 0.6s ease; }
    .card:hover { transform: translateY(-10px) scale(1.03); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
    .card img { height: 220px; object-fit: cover; }
    .btn-warning

<!DOCTYPE html>
<html>
<head>
  <title>YS Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  background: linear-gradient(120deg, #f6f7fb, #e9ecf5);
  font-family: 'Segoe UI', sans-serif;
}

/* HERO */
.hero {
  background: linear-gradient(135deg, #000, #2c2c2c);
  color: white;
  padding: 70px;
  border-radius: 20px;
  margin-bottom: 40px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

/* CARDS */
.card {
  border: none;
  border-radius: 20px;
  overflow: hidden;
  transition: 0.4s;
  animation: fadeInUp 0.6s ease;
}

.card:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

/* IMAGE */
.card img {
  height: 220px;
  object-fit: cover;
}

/* BADGE */
.badge {
  font-size: 12px;
  padding: 6px 10px;
  border-radius: 10px;
}

/* BOUTON */
.btn-warning {
  background: linear-gradient(45deg, #ffb300, #ffcc00);
  border: none;
}

/* ANIMATION */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

</head>

<body class="container mt-4">

<!-- MESSAGE SUCCES -->
<?php if (isset($_GET['success'])): ?>
  <div id="successMsg" class="alert alert-success text-center shadow">
    🎉 Ajouté aux favoris !
  </div>
<?php endif; ?>

<!-- NAVBAR -->
<div class="d-flex justify-content-between align-items-center mb-4">

  <h3 class="fw-bold">🟡 YS </h3>

  <form method="GET" class="d-flex">
    <input type="text" name="q" class="form-control me-2" placeholder="Rechercher...">
    <button class="btn btn-dark">OK</button>
  </form>

  <div>
    <a href="favoris.php" class="btn btn-warning me-2">
      ❤️ Favoris (<?= $count ?>)
    </a>
    <a href="add.php" class="btn btn-success">➕ Poster</a>
  </div>

</div>

<!-- HERO -->
<div class="hero">
  <h1 class="fw-bold">Achetez & vendez en toute confiance 🚀</h1>
  <p>Plateforme moderne d'annonces</p>
</div>

<!-- FILTRE -->
<div class="mb-4">
  <a href="recherche.php?cat=Voiture" class="btn btn-outline-dark">Voiture</a>
  <a href="recherche.php?cat=Maison" class="btn btn-outline-dark">Maison</a>
  <a href="recherche.php?cat=Electronique" class="btn btn-outline-dark">Electronique</a>
</div>

<!-- ANNONCES -->
<div class="row">

<?php foreach($annonces as $a): ?>

  <div class="col-md-4">
    <div class="card shadow mb-4">

      <img src="<?= $a['image_url'] ?>">

      <div class="card-body">

        <span class="badge bg-warning text-dark mb-2">
          <?= $a['categorie'] ?>
        </span>

        <h5 class="fw-bold"><?= $a['titre'] ?></h5>

        <p><?= $a['description'] ?></p>

        <a href="add_fav.php?id=<?= $a['id'] ?>" class="btn btn-warning w-100 fw-bold">
          ❤️ Favori
        </a>

      </div>
    </div>
  </div>

<?php endforeach; ?>

</div>

<!-- SCRIPT ANIMATION MESSAGE -->
<script>
setTimeout(() => {
  let msg = document.getElementById("successMsg");
  if (msg) {
    msg.style.transition = "0.5s";
    msg.style.opacity = "0";
    msg.style.transform = "translateY(-20px)";
  }
}, 2000);
</script>

</body>
</html>
