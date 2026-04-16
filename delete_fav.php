<?php
require 'config/db_connect.php';

if (isset($_GET['id'])) {
    $annonce_id = $_GET['id'];

    $query = "DELETE FROM favoris WHERE annonce_id = ?";
    $stmt = mysqli_prepare($connexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $annonce_id);
    mysqli_stmt_execute($stmt);
}

header("Location: favoris.php");
exit;