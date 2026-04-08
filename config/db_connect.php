<?php
$serveur = "127.0.0.1";
$utilisateur = "root";
$mot_de_passe = "root";
$base_de_donnees = "ys_database";


$connexion = @mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees, $port);

if (!$connexion) {
    $port = 8889;
    $connexion = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees, $port);
}

if (!$connexion) {
    die("Erreur de connexion : " . mysqli_connect_error());
}
?>