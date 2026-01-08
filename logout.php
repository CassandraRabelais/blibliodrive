<?php
// ======================================
// PAGE DE DÉCONNEXION
// ======================================
// Détruit la session utilisateur et redirige vers la page d'accueil

session_start();
session_destroy(); // Détruire toutes les données de session
header("Location: login.php"); // Rediriger vers la page de connexion
exit();
?>