<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$host = 'localhost';
$db   = 'ys_database';
$user = 'root';
$pass = 'root';
$pdo  = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT * FROM annonces WHERE user_id = :user_id ORDER BY date_publication DESC");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

$base_url = '//' . $_SERVER['HTTP_HOST'] . '/Projet-DevWeb-YS/';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mes annonces</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        h1 { font-size: 22px; font-weight: 500; margin-bottom: 1.5rem; }
        .success { color: green; margin-bottom: 1rem; }
        .vide { color: #888; }
        .grille { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; }
        .carte { border: 0.5px solid #ddd; border-radius: 12px; overflow: hidden; background: #fff; }
        .carte img { width: 100%; height: 180px; object-fit: cover; background: #f5f5f5; }
        .carte-body { padding: 0.75rem 1rem; }
        .carte-titre { font-size: 15px; font-weight: 500; margin: 0 0 4px; }
        .carte-prix { font-size: 16px; color: #333; margin: 0 0 4px; }
        .carte-etat { font-size: 12px; color: #888; margin: 0 0 8px; }
        .carte-desc { font-size: 13px; color: #555; margin: 0 0 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .carte-date { font-size: 11px; color: #aaa; }
        .btn-nouvelle { display: inline-block; margin-bottom: 1.5rem; padding: 8px 16px; border: 0.5px solid #ccc; border-radius: 8px; text-decoration: none; color: #333; font-size: 14px; }
        .btn-nouvelle:hover { background: #f5f5f5; }
    </style>
</head>
<body>

<h1>Mes annonces</h1>

<?php if (isset($_GET['success'])): ?>
    <p class="success">Annonce publiée avec succès !</p>
<?php endif; ?>

<a href="annonce.php" class="btn-nouvelle">+ Nouvelle annonce</a>

<?php if (empty($annonces)): ?>
    <p class="vide">Tu n'as pas encore d'annonces.</p>
<?php else: ?>
    <div class="grille">
        <?php foreach ($annonces as $a): ?>
            <div class="carte">
                <?php if ($a['image_url']): ?>
                    <img src="<?= '//' . $_SERVER['HTTP_HOST'] . '/Projet-DevWeb-YS/' . htmlspecialchars($a['image_url']) ?>" alt="<?= htmlspecialchars($a['titre']) ?>">
                <?php else: ?>
                    <div style="width:100%; height:180px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#aaa; font-size:13px;">Pas d'image</div>
                <?php endif; ?>
                <div class="carte-body">
                    <p class="carte-titre"><?= htmlspecialchars($a['titre']) ?></p>
                    <p class="carte-prix"><?= number_format($a['prix'], 2, ',', ' ') ?> €</p>
                    <p class="carte-etat"><?= htmlspecialchars($a['etat']) ?></p>
                    <p class="carte-desc"><?= htmlspecialchars($a['description']) ?></p>
                    <p class="carte-date"><?= date('d/m/Y à H:i', strtotime($a['date_publication'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>