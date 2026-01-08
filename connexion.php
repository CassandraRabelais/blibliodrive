<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style.css" rel="stylesheet">
    <title>Connexion</title>
</head>
<body>
    <?php
		// ==========================================
		// FICHIER DE CONNEXION À LA BASE DE DONNÉES
		// ==========================================
		// Ce fichier établit la connexion à la base de données MySQL (bibliodrive)
		// Utilise PDO (PHP Data Objects) pour une connexion sécurisée
		
		try {
			// Définir les paramètres de connexion à la base de données
			$dns = 'mysql:host=localhost;dbname=bibliodrive'; // Adresse du serveur et nom de la BD
			$utilisateur = 'root'; // Utilisateur MySQL
			$motDePasse = ''; // Mot de passe (vide par défaut pour root en local)
			
			// Créer la connexion PDO
			$connexion = new PDO($dns, $utilisateur, $motDePasse);
		} catch (PDOException $e) {
			// En cas d'erreur de connexion, afficher le message d'erreur
			echo 'Échec de la connexion : ' . $e->getMessage();
		}
	?>
</body>
</html>