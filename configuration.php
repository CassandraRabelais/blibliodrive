<?php
// ======================================
// FICHIER DE CONFIGURATION
// ======================================
// Fichier destiné à contenir les paramètres de configuration de l'application
// À compléter selon les besoins du projet (variables globales, constantes, etc.)

session_start();

// Vérifier si l'utilisateur a demandé la déconnexion
if (isset($_POST['deco'])) {
    session_unset(); // Effacer toutes les variables de session
    session_destroy(); // Détruire la session
    header('Location: index.php'); // Rediriger vers l'accueil
    exit();
}

// Inclure la connexion à la base de données
require_once('connexion.php');

// Afficher l'en-tête selon le profil utilisateur
// (Fonction réservée aux admins pour le moment)
if (isset($_SESSION['profil']) && $_SESSION['profil'] === 'admin') {
    include 'navbar.php';
} 
?>