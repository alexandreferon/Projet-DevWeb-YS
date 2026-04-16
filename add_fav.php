<?php
require 'config/db_connect.php';

if (isset($_GET['id'])) {
    $annonce_id = $_GET['id'];
    $utilisateur_id = 15; // Exemple.

    $query = "INSERT INTO favoris (utilisateur_id, annonce_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($connexion, $query);
    mysqli_stmt_bind_param($stmt, "ii", $utilisateur_id, $annonce_id);
    mysqli_stmt_execute($stmt);
}

header("Location: recherche.php?success=1");
exit;