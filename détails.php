<!DOCTYPE html>
<html lang="en">
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style.css" rel="stylesheet">
	<title>Détails du livre</title>
</head>
<body>
	
    <?php 
    // ======================================
    // PAGE DE DÉTAILS D'UN LIVRE
    // ======================================
    // Affiche les informations complètes d'un livre sélectionné
    // Permet d'ajouter le livre au panier si l'utilisateur est connecté
    
    session_start();
    require_once 'navbar.php'; 
    $message = '';
    ?>

	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<?php
				// Inclure la connexion à la base de données
				require_once 'connexion.php';
				$livre = null;
				$searchQuery = isset($_GET['author']) ? trim($_GET['author']) : '';
				
				// Vérifier si un numéro de livre valide a été fourni
				if (isset($_GET['nolivre']) && is_numeric($_GET['nolivre'])) {
					$nolivre = (int) $_GET['nolivre'];
					// Récupérer les infos du livre et de son auteur
					$sql = "SELECT l.titre, l.photo, l.isbn13, l.anneeparution, l.detail, l.dateajout, a.nom, a.prenom 
							FROM livre l 
							JOIN auteur a ON l.noauteur = a.noauteur 
							WHERE l.nolivre = :nolivre";
					$stmt = $connexion->prepare($sql);
					$stmt->bindParam(':nolivre', $nolivre, PDO::PARAM_INT);
					$stmt->execute();
					$livre = $stmt->fetch(PDO::FETCH_ASSOC);
				}

				// ========== AJOUTER LE LIVRE AU PANIER ==========
				if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart']) && isset($_SESSION['user'])) {
					// Initialiser le panier s'il n'existe pas
					if (!isset($_SESSION['cart'])) {
						$_SESSION['cart'] = [];
					}
					// Vérifier si le livre n'est pas déjà dans le panier
					if (!in_array($nolivre, $_SESSION['cart'])) {
						// Vérifier que le panier ne dépasse pas 5 livres
								$_SESSION['cart'][] = $nolivre;
								$message = '<div class="alert alert-success">Livre ajouté au panier.</div>';
							} else {
								$message = '<div class="alert alert-danger">Panier plein. Limite de 5 livres atteinte.</div>';
							}
						} else {
							$message = '<div class="alert alert-warning">Ce livre est déjà dans votre panier.</div>';
						}
				?>

				<?php if ($livre): ?>
				<!-- Afficher les détails du livre -->
				<div class="row">
					<div class="col-md-4">
						<!-- Afficher la couverture du livre -->
						<img src="covers/<?php echo htmlspecialchars($livre['photo']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($livre['titre']); ?>" style="max-height:400px; object-fit:contain;">
					</div>
					<div class="col-md-8">
						<!-- Titre du livre -->
						<h2><?php echo htmlspecialchars($livre['titre']); ?></h2>
						<!-- Informations du livre -->
						<p><strong>Auteur :</strong> <?php echo htmlspecialchars($livre['prenom'] . ' ' . $livre['nom']); ?></p>
						<p><strong>ISBN-13 :</strong> <?php echo htmlspecialchars($livre['isbn13']); ?></p>
						<p><strong>Année de parution :</strong> <?php echo htmlspecialchars($livre['anneeparution']); ?></p>
						<p><strong>Date d'ajout :</strong> <?php echo htmlspecialchars($livre['dateajout']); ?></p>
						<h5>Description :</h5>
						<p><?php echo nl2br(htmlspecialchars($livre['detail'])); ?></p>
						<!-- Afficher les messages -->
						<?php echo $message; ?>
						<!-- Indication de disponibilité -->
						<p class="disponible" >Disponible</p>
						<!-- Message si l'utilisateur n'est pas connecté -->
						<?php if (!isset($_SESSION['user'])): ?>
							<p class="indication">Pour pouvoir vous identifier, vous devez posséder un compte et vous connecter.</p>
						<?php endif; ?>
						<!-- Bouton ajouter au panier si connecté -->
						<?php if (isset($_SESSION['user'])): ?>
							<form method="post" style="display: inline;">
								<button type="submit" name="add_to_cart" class="btn btn-primary">Ajouter au panier</button>
							</form>
						<?php endif; ?>
						<!-- Lien retour à la recherche -->
						<a href="Recherche.php<?php echo !empty($searchQuery) ? '?author=' . urlencode($searchQuery) : ''; ?>" class="btn btn-secondary">Retour à la recherche</a>
					</div>
				</div>
			<?php else: ?>
				<!-- Message d'erreur si livre non trouvé -->
						Livre non trouvé ou numéro invalide.
					</div>
					<a href="Recherche.php<?php echo !empty($searchQuery) ? '?author=' . urlencode($searchQuery) : ''; ?>" class="btn btn-secondary">Retour à la recherche</a>
				<?php endif; ?>

				<!-- Bootstrap JS -->
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
			</div>

			<?php include 'formulaire.php'; ?>
		</div>
	</div>

</body>
</html>