<?php
require_once 'configuration.php';

// Préparation de la requête pour récupérer les auteurs
$stmt = $connexion->prepare("SELECT noauteur, nom, prenom FROM auteur ORDER BY nom, prenom");
$stmt->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Ajouter un nouveau livre</h2>
        <form method="post">
            <div class="mb-3">
                <label for="noauteur" class="form-label">Auteur:</label>
                <select name="noauteur" class="form-control" id="noauteur" required>
                    <?php while ($auteur = $stmt->fetch(PDO::FETCH_OBJ)): ?>
                        <option value="<?= $auteur->noauteur ?>"><?= $auteur->prenom . ' ' . $auteur->nom ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="titre" class="form-label">Titre:</label>
                <input type="text" name="titre" class="form-control" id="titre" required>
            </div>
            <div class="mb-3">
                <label for="isbn13" class="form-label">ISBN13:</label>
                <input type="text" name="isbn13" class="form-control" id="isbn13" required>
            </div>
            <div class="mb-3">
                <label for="anneeparution" class="form-label">Année de parution:</label>
                <input type="text" name="anneeparution" class="form-control" id="anneeparution" required>
            </div>
            <div class="mb-3">
                <label for="detail" class="form-label">Résumé:</label>
                <textarea name="detail" class="form-control" id="detail" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Image:</label>
                <input type="text" name="photo" class="form-control" id="photo" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter livre</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Préparation de la requête d'insertion
        $insertStmt = $connexion->prepare("INSERT INTO livre (noauteur, titre, isbn13, anneeparution, detail, photo) VALUES (:noauteur, :titre, :isbn13, :anneeparution, :detail, :photo)");
        
        // Récupération des valeurs du formulaire
        $noauteur = $_POST['noauteur'];
        $titre = $_POST['titre'];
        $isbn13 = $_POST['isbn13'];
        $anneeparution = $_POST['anneeparution'];
        $detail = $_POST['detail'];
        $photo = $_POST['photo'];
        
        // Liaison des valeurs avec bindValue et spécification des types
        $insertStmt->bindValue(':noauteur', $noauteur, PDO::PARAM_INT);
        $insertStmt->bindValue(':titre', $titre, PDO::PARAM_STR);
        $insertStmt->bindValue(':isbn13', $isbn13, PDO::PARAM_STR);
        $insertStmt->bindValue(':anneeparution', $anneeparution, PDO::PARAM_INT);
        $insertStmt->bindValue(':detail', $detail, PDO::PARAM_STR);
        $insertStmt->bindValue(':photo', $photo, PDO::PARAM_STR);
        
        // Exécution de la requête
        $insertStmt->execute();
        $nb_ligne_affectees = $insertStmt->rowCount();
        
        if ($nb_ligne_affectees > 0) {
            $dernier_numero = $connexion->lastInsertId();
            echo "<div class='container mt-4'><div class='alert alert-success'>";
            echo $nb_ligne_affectees . " livre(s) ajouté(s) avec succès.<br>";
            echo "Dernier numéro de livre généré : " . $dernier_numero;
            echo "</div></div>";
        } else {
            echo "<div class='container mt-4'><div class='alert alert-danger'>Erreur lors de l'ajout du livre.</div></div>";
        }
    }
    ?>
</body>
</html>