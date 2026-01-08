<!DOCTYPE html>
<html lang="en">
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style.css" rel="stylesheet">
	<title>Résultats de recherche</title>
</head>
<body>
	
	<?php
		// ======================================
		// PAGE DE RECHERCHE DE LIVRES
		// ======================================
		// Permet de chercher des livres par le nom de l'auteur
		// Affiche les résultats sous forme de liste avec possibilité d'ajouter au panier
		
		session_start();
		require_once 'navbar.php';
	?>

	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<!-- Message informatif sur la limite d'emprunts -->
				<div class="alert alert-info" role="alert">
					Vous pouvez emprunter jusqu'à 5 livres maximum.
				</div>
				<?php
					// Inclure la connexion à la base de données
					require_once 'connexion.php';
					$livres = [];
					$searchQuery = '';
					
					// Vérifier si une recherche a été effectuée
					if (isset($_GET['author']) && trim($_GET['author']) !== '') {
						// Récupérer le terme de recherche
						$searchQuery = trim($_GET['author']);
						
						// Requête SQL pour chercher par nom ou prénom d'auteur
						$sql = "SELECT DISTINCT l.nolivre, l.titre, l.photo, a.nom, a.prenom FROM livre l 
								JOIN auteur a ON l.noauteur = a.noauteur 
								WHERE LOWER(a.nom) LIKE LOWER(:s) 
								OR LOWER(a.prenom) LIKE LOWER(:s) 
								OR LOWER(CONCAT(a.prenom, ' ', a.nom)) LIKE LOWER(:s) 
								OR LOWER(CONCAT(a.nom, ' ', a.prenom)) LIKE LOWER(:s)
								ORDER BY l.dateajout DESC";
						$stmt = $connexion->prepare($sql);
						$param = '%'.$searchQuery.'%';
						$stmt->bindParam(':s', $param, PDO::PARAM_STR);
						$stmt->execute();
						$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}
				?>

				<?php if (!empty($searchQuery)): ?>
				<!-- Afficher le titre avec le terme recherché -->
				<h2 class="mb-3">Résultats de recherche pour "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
				
				<!-- Vérifier si des livres ont été trouvés -->
				<?php if (!empty($livres)): ?>
					<!-- Afficher la liste des livres trouvés -->
					<ul class="list-group">
						<?php foreach ($livres as $livre): ?>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<!-- Lien vers la page de détails du livre -->
								<a href="détails.php?nolivre=<?php echo htmlspecialchars($livre['nolivre'] ?? ''); ?>&author=<?php echo urlencode($searchQuery); ?>" class="text-decoration-none"><?php echo htmlspecialchars($livre['titre']); ?></a>
								<!-- Bouton ajouter au panier si connecté -->
								<?php if (isset($_SESSION['user'])): ?>
									<form method="post" action="détails.php?nolivre=<?php echo htmlspecialchars($livre['nolivre'] ?? ''); ?>&author=<?php echo urlencode($searchQuery); ?>" style="display: inline;">
										<input type="hidden" name="add_to_cart" value="1">
										<button type="submit" class="btn btn-sm btn-primary">Ajouter au panier</button>
									</form>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<!-- Message si aucun livre trouvé -->
					<div class="alert alert-warning" role="alert">
						Aucun livre trouvé pour l'auteur "<?php echo htmlspecialchars($searchQuery); ?>".
					</div>
				<?php endif; ?>
			<?php else: ?>
					<!-- Message initial si aucune recherche -->
							Veuillez saisir le nom d'un auteur dans la barre de recherche pour afficher les résultats.
					</div>
				<?php endif; ?>

				<!-- Bootstrap JS -->
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
			</div>

			<?php include 'formulaire.php'; ?></div>
		</div>
	</div>

</body>
</html>