<?php
session_start();
require 'config.php';

$message = "";

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        $message = "❌ Veuillez remplir tous les champs.";
    } else {
        // Vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['mot_de_passe'])) {

                // Stocker les infos dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['prenom']  = $user['prenom'];
                $_SESSION['nom']     = $user['nom'];
                $_SESSION['role']    = $user['role'];

                // Redirection selon le rôle
                switch ($user['role']) {
                    case 'admin':
                        header("Location: admin_dashboard.php");
                        break;
                    case 'employe':
                        header("Location: employe_commandes.php");
                        break;
                    default:
                        header("Location: mes_commandes.php");
                }
                exit;

            } else {
                $message = "❌ Mot de passe incorrect.";
            }
        } else {
            $message = "❌ Utilisateur non trouvé.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Vite & Gourmand</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Header */
        .header-link { transition: color 0.3s; color: black; }
        .header-link:hover { color: #007bff; }
        .btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
        .user-name { font-size: 0.75rem; line-height: 1rem; display: block; }
        .navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }
    </style>
</head>
<body>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item mx-2"><a class="nav-link header-link" href="index.php">Accueil</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="mes_menus.php">Mes Menus</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="mes_commandes.php">Mes Commandes</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="contact.php">Contact</a></li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-danger btn-hover-red px-3" href="deconnexion.php">
                            Déconnexion
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-success btn-hover-white px-3" href="mes_commandes.php">
                            Connexion
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- FORMULAIRE DE CONNEXION -->
<div class="container d-flex justify-content-center align-items-center my-5">
    <div class="card p-4 shadow" style="width: 100%; max-width: 500px;">
        <h2 class="mb-4 text-center">Connexion</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="row g-3">
            <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required
                       value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            </div>

            <div class="col-12">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary w-100 mt-3">
                    Se connecter
                </button>
            </div>
        </form>

        <div class="mt-3 text-center">
            <p>Vous n'avez pas encore de compte ?
                <a href="inscription.php">Créer un compte</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
