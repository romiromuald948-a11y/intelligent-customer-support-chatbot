<?php
// Activer l'affichage des erreurs pour le test
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$_SESSION['user_id'] = 1; // ID utilisateur de test

// Simuler une requête POST comme si ça venait du navigateur
$_POST['message'] = "Bonjour Green Chat, peux-tu me donner un produit ?";
$_POST['incoming_id'] = 9999;

// Inclure insert_chat.php
include "insert_chat.php";
