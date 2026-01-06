<?php
require_once('configuration.php');
// Vérifier que l'utilisateur est administrateur
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter un membre</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Ajouter un nouveau membre</h2>
        <form method="post">
            <div class="mb-3">
                <label for="mel" class="form-label">Mel:</label>
                <input type="email" name="mel" class="form-control" id="mel" required>
            </div>
            <div class="mb-3">
                <label for="motdepasse" class="form-label">Mot de passe:</label>
                <input type="password" name="motdepasse" class="form-control" id="motdepasse" required>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" name="nom" class="form-control" id="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prenom:</label>
                <input type="text" name="prenom" class="form-control" id="prenom" required>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse:</label>
                <input name="adresse" class="form-control" id="adresse" required></input>
            </div>
            <div class="mb-3">
                <label for="ville" class="form-label">Ville:</label>
                <input type="text" name="ville" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le membre</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Préparation de la requête d'insertion
        $insertStmt = $connexion->prepare("INSERT INTO utilisateur (mel, motdepasse, nom, prenom, adresse, ville, profil) VALUES (:mel, :motdepasse, :nom, :prenom, :adresse, :ville, 'membre')");
        
        // Récupération des valeurs du formulaire
        $mel = $_POST['mel'];
        $motdepasse = $_POST['motdepasse'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $adresse = $_POST['adresse'];
        $ville = $_POST['ville'];
        
        // Liaison des paramètres
        $insertStmt->bindValue(':mel', $mel);
        $insertStmt->bindValue(':motdepasse', $motdepasse);
        $insertStmt->bindValue(':nom', $nom);
        $insertStmt->bindValue(':prenom', $prenom);
        $insertStmt->bindValue(':adresse', $adresse);
        $insertStmt->bindValue(':ville', $ville);
        
        // Exécution de la requête
        if ($insertStmt->execute()) {
            echo "<div class='container mt-4'><div class='alert alert-success'>Membre ajouté avec succès.</div></div>";
        } else {
            echo "<div class='container mt-4'><div class='alert alert-danger'>Erreur lors de l'ajout du membre.</div></div>";
        }
    }
    ?>
</body>