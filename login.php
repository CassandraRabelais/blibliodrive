<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Connexion</title>
</head>
<body>
    <?php
    // ======================================
    // PAGE DE CONNEXION UTILISATEUR
    // ======================================
    // Permet aux utilisateurs de se connecter avec email et mot de passe
    
    session_start(); // Démarrer la session PHP
    require_once 'connexion.php'; // Inclure le fichier de connexion à la base de données

    // Vérifier si le formulaire a été soumis en POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupérer les données du formulaire
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Préparer la requête SELECT pour chercher l'utilisateur
        $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE mel = ? AND motdepasse = ?");
        $stmt->execute([$email, $password]); // Exécuter la requête avec email et mot de passe
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupérer le résultat sous forme de tableau

        if ($user) {
            // Si l'utilisateur est trouvé, le stocker dans la session
            $_SESSION['user'] = $user;
            // Rediriger vers la page d'accueil
            header('Location: index.php');
            exit;
        } else {
            // Si identifiants incorrects, afficher un message d'erreur
            $error = "Email ou mot de passe incorrect.";
        }
    }
    ?>

    <?php require_once 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Connexion</h5>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                            
                        <form method="post" action="login.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
