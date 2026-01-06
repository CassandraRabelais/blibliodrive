<?php
require 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<h2>Admin - Ajouter un utilisateur</h2>

<form action="add_user.php" method="POST">
    <input name="username" placeholder="Nom d'utilisateur">
    <input type="password" name="password" placeholder="Mot de passe">
    <button>Ajouter</button>
</form>
