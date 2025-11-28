<!DOCTYPE html>
<html lang="en">
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  	rel="stylesheet">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Page d'acceuil</title>
<html>
</head>
<body>
	La bibliothèque de Moulinsart est fermée au public jusqu'à nouvel ordre. Mais il vous est possible de réserver et retirer vos livres via notre service Biblio Drive !

<div class="container-fluid">
	<div class="row">
		<div class="col-md-9">
			<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
				<form class="form-inline" action="/action_page.php">
					<input class="form-control mr-sm-2" type="text" placeholder="Rechercher dans le catalogue (saisie du nom de l'auteur)" size="40">
					<button class="btn btn-success" type="submit">Recherche</button>
				</form>
			</nav><br>			
		</div>

		<div class="col-md-3">

		</div>
	</div>
	<div class="row">
		<div class="col-md-9">
			<?php
				try {

				$dns = 'mysql:host=localhost;dbname=bibliodrive projet';
				$utilisateur = 'root';
				$motDePasse = '';
				$connexion = new PDO($dns, $utilisateur, $motDePasse);

				} catch (PDOException $e) {
				echo 'Échec de la connexion : ' . $e->getMessage();
				}
			?>
			<?php
				$stmt = $connexion->prepare("SELECT titre, photo FROM livre ORDER BY dateajout DESC LIMIT 3");
				$stmt->execute();
			?>

			<h2>Dernières acquisitions</h2>
			
			</div>
		</div>
	</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


		</div>

</body>
</html>
	