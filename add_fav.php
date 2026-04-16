<?php
session_start();
include('config/db_connect.php');

// 1. Vérification de session
if (!isset($_SESSION['user_id'])) {
    die("Erreur : Vous n'êtes pas connecté. Votre session est peut-être expirée.");
}

$user_id = $_SESSION['user_id'];
$annonce_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($annonce_id <= 0) {
    die("Erreur : ID d'annonce invalide.");
}

// 2. Debug : On vérifie si l'utilisateur existe vraiment en base
$check_user = mysqli_query($connexion, "SELECT id FROM utilisateurs WHERE id = $user_id");
if (mysqli_num_rows($check_user) == 0) {
    die("Erreur critique : L'ID utilisateur ($user_id) dans votre session n'existe pas dans la table 'utilisateurs'. Déconnectez-vous et reconnectez-vous.");
}

// 3. Vérifier si l'annonce existe
$check_annonce = mysqli_query($connexion, "SELECT id FROM annonces WHERE id = $annonce_id");
if (mysqli_num_rows($check_annonce) == 0) {
    die("Erreur : L'annonce n'existe pas.");
}

// 4. Vérifier si déjà en favori
$stmt_check = mysqli_prepare($connexion, "SELECT id FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?");
mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $annonce_id);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) == 0) {
    // 5. Insertion
    $stmt_ins = mysqli_prepare($connexion, "INSERT INTO favoris (utilisateur_id, annonce_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt_ins, "ii", $user_id, $annonce_id);
    
    if (mysqli_stmt_execute($stmt_ins)) {
        // Succès
        header("Location: favoris.php");
        exit;
    } else {
        // Affichage de l'erreur SQL précise
        die("Erreur SQL lors de l'insertion : " . mysqli_error($connexion));
    }
} else {
    // Déjà présent, on redirige simplement
    header("Location: favoris.php");
    exit;
}