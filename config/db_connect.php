<?php
$serveur = "127.0.0.1"; // <-- C'est cette ligne qui a changé
$utilisateur = "root";
$mot_de_passe = "root";
$base_de_donnees = "ys_database";
$port = 8889; 

// 1. Connexion au serveur avec MySQLi
$connexion = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees, $port);

if (!$connexion) {
    die("Erreur de connexion au serveur : " . mysqli_connect_error());
}
?>