<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $pdo->prepare("INSERT INTO favoris (annonce_id) VALUES (?)")
        ->execute([$id]);
}

// REDIRECTION AVEC MESSAGE
header("Location: index.php?success=1");
exit;
