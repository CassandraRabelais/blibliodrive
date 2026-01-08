<!DOCTYPE html>
<html lang="en">
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style.css" rel="stylesheet">
	<title>Page d'acceuil</title>
</head>
<body>
	
<?php 
// ======================================
// PAGE D'ACCUEIL PRINCIPALE
// ======================================
// Affiche un carousel des 3 derniers livres ajoutés
// Affiche les informations de l'utilisateur s'il est connecté
// Sinon affiche un formulaire de connexion et d'inscription

session_start(); // Démarrer la session
require_once 'navbar.php'; // Inclure la barre de navigation
?>
	
	<div class="container">
	<div class="row">
		<div class="col-md-9">
			
			<?php
				// Inclure la connexion à la base de données
				require_once 'connexion.php';
				
				// Récupérer les 3 derniers livres ajoutés à la BD
				$stmt = $connexion->prepare("SELECT titre, photo FROM livre ORDER BY dateajout DESC LIMIT 3");
				$stmt->execute();
				$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
			?>
			<h2 class="mb-3">Dernières acquisitions</h2>		
			<!-- Carousel Bootstrap affichant les 3 derniers livres -->			
			<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
				<div class="carousel-inner">
				<!-- Afficher chaque livre dans le carousel -->
				<?php foreach ($livres as $index => $livre): ?>
					<div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
					<!-- Afficher la couverture du livre -->
					</div>
					<?php endforeach; ?>
				</div>

				<!-- Boutons précédent / suivant -->
				<button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
					<span class="carousel-control-prev-icon"></span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
					<span class="carousel-control-next-icon"></span>
				</button>
			</div>
			<!-- Bootstrap JS -->
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
		</div>
		<?php if (isset($_SESSION['user'])): ?>
			<!-- Si l'utilisateur est connecté, afficher ses informations personnelles -->
			<div class="col-md-3 d-flex align-items-start justify-content-end">
				<div class="card w-100" style="max-width: 350px;">
					<div class="card-body">
						<h5 class="card-title">Informations utilisateur</h5>
						<!-- Afficher les infos récupérées de la session -->
						<p><strong>Nom :</strong> <?php echo htmlspecialchars($_SESSION['user']['nom']); ?></p>
						<p><strong>Prénom :</strong> <?php echo htmlspecialchars($_SESSION['user']['prenom']); ?></p>
						<p><strong>Email :</strong> <?php echo htmlspecialchars($_SESSION['user']['mel']); ?></p>
						<p><strong>Adresse :</strong> <?php echo htmlspecialchars($_SESSION['user']['adresse']); ?></p>
						<p><strong>Ville :</strong> <?php echo htmlspecialchars($_SESSION['user']['ville']); ?></p>
					</div>
				</div>
			</div>
		<?php else: ?>
			<!-- Si l'utilisateur n'est pas connecté, afficher le formulaire de connexion -->
			<?php include 'formulaire.php'; ?>
		<?php endif; ?>
	</div>
	</div>
</body>
</html>
	