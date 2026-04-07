<?php
session_start(); // On récupère la session en cours

// On vide toutes les variables de session (comme l'ID et le nom)
session_unset(); 

// On détruit complètement la session
session_destroy(); 

// On renvoie l'utilisateur vers la page de connexion
header("Location: connexion.php");
exit();
?>