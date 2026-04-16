<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $pdo->prepare("DELETE FROM favoris WHERE annonce_id = ?")
        ->execute([$id]);
}

header("Location: favoris.php");