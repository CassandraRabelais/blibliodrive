<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Panier</title>
</head>
<body>
    <?php
    // ======================================
    // PAGE DU PANIER
    // ======================================
    // Permet aux utilisateurs de voir, ajouter et supprimer des livres
    // Permet d'emprunter jusqu'à 5 livres en même temps
    
    session_start();
    // Initialiser le panier s'il n'existe pas
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    require_once 'navbar.php';
    require_once 'connexion.php';
    $message = '';
    
    // ========== RETIRER UN LIVRE DU PANIER ==========
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
        // Récupérer le numéro du livre à retirer
        $nolivre = (int) $_POST['nolivre'];
        // Chercher et supprimer le livre du tableau de session
        if (($key = array_search($nolivre, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Réindexer le tableau
        }
    }

    // ========== EMPRUNTER TOUS LES LIVRES ==========
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_all']) && isset($_SESSION['user']) && !empty($_SESSION['cart'])) {
        $userEmail = $_SESSION['user']['mel'];
        
        // Vérifier le nombre de livres actuellement empruntés par l'utilisateur
        $currentBorrowedSql = "SELECT COUNT(*) FROM emprunter WHERE mel = :mel AND dateretour IS NULL";
        $currentBorrowedStmt = $connexion->prepare($currentBorrowedSql);
        $currentBorrowedStmt->bindParam(':mel', $userEmail);
        $currentBorrowedStmt->execute();
        $currentBorrowed = $currentBorrowedStmt->fetchColumn();
        
        // Vérifier si l'ajout ne dépasse pas la limite de 5 livres
        if ($currentBorrowed + count($_SESSION['cart']) > 5) {
            // Dépassement de la limite, on ne procède pas
        } else {
            $success = true;
            // Pour chaque livre du panier
            foreach ($_SESSION['cart'] as $nolivre) {
                // Vérifier si le livre n'est pas déjà emprunté par cet utilisateur
                $checkSql = "SELECT * FROM emprunter WHERE mel = :mel AND nolivre = :nolivre AND dateretour IS NULL";
                $checkStmt = $connexion->prepare($checkSql);
                $checkStmt->bindParam(':mel', $userEmail);
                $checkStmt->bindParam(':nolivre', $nolivre);
                $checkStmt->execute();
                
                if ($checkStmt->rowCount() == 0) {
                    // Insérer l'emprunt dans la base de données
                    $borrowSql = "INSERT INTO emprunter (mel, nolivre, dateemprunt) VALUES (:mel, :nolivre, CURDATE())";
                    $borrowStmt = $connexion->prepare($borrowSql);
                    $borrowStmt->bindParam(':mel', $userEmail);
                    $borrowStmt->bindParam(':nolivre', $nolivre);
                    if (!$borrowStmt->execute()) {
                        $success = false;
                    }
                }
            }
            if ($success) {
                $message = '<div class="alert alert-success">Tous les livres ont été empruntés avec succès !</div>';
            }
        }
        $_SESSION['cart'] = []; // Vider le panier après l'emprunt
    }

    // ========== RÉCUPÉRER LES DÉTAILS DES LIVRES DU PANIER ==========
    $cartBooks = [];
    if (!empty($_SESSION['cart'])) {
        // Créer des placeholders pour la requête IN
        $placeholders = str_repeat('?,', count($_SESSION['cart']) - 1) . '?';
        // Récupérer le titre, auteur et photo des livres du panier
        $sql = "SELECT l.nolivre, l.titre, l.photo, a.nom, a.prenom FROM livre l JOIN auteur a ON l.noauteur = a.noauteur WHERE l.nolivre IN ($placeholders)";
        $stmt = $connexion->prepare($sql);
        $stmt->execute($_SESSION['cart']);
        $cartBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-9">
                <h1>Votre Panier</h1>
                <!-- Afficher les messages (succès ou erreur) -->
                <?php echo $message; ?>
                <!-- Vérifier si le panier est vide -->
                <?php if (empty($cartBooks)): ?>
                    <p>Votre panier est vide.</p>                                                       
                <?php else: ?>
                    <!-- Afficher la liste des livres du panier -->
                    <ul class="list-group">
                        <?php foreach ($cartBooks as $book): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <!-- Titre du livre -->
                                <?php echo htmlspecialchars($book['titre']); ?>
                                <!-- Formulaire de suppression -->
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="nolivre" value="<?php echo $book['nolivre']; ?>">
                                    <button type="submit" name="remove" class="btn btn-danger btn-sm">Retirer</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Bouton pour emprunter si l'utilisateur est connecté -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <form method="post" class="mt-3">
                            <button type="submit" name="borrow_all" class="btn btn-success">Emprunter tous les livres</button>
                        </form>
                    <?php else: ?>
                        <p class="alert alert-warning mt-3">Vous devez être connecté pour emprunter.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php include 'formulaire.php'; ?>
-            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>