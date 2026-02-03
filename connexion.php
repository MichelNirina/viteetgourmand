<?php
session_start();
include "config.php";

$message = "";

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        $message = "❌ Veuillez remplir tous les champs.";
    } else {
        // Vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérification du mot de passe hashé
            if (password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nom'];
                $_SESSION['role'] = $user['role'];

                // Redirection selon le rôle
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['role'] === 'employe') {
                    header("Location: employe_commandes.php");
                } else {
                    header("Location: index.php"); // utilisateur normal
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
</head>
<body>

<!-- Header responsive -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#menus">Menus</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Formulaire de connexion centré et responsive -->
<div class="container d-flex justify-content-center align-items-center my-5">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <h2 class="mb-4 text-center">Connexion</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" name="email" id="email" class="form-control" required 
                       value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

            <div class="mt-3 text-center">
                <p>Vous n'avez pas encore de compte ? <a href="creecompte.php">Créer un compte</a></p>
           </div>


    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
