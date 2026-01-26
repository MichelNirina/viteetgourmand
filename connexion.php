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
    <title>Connexion - Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Header simplifié -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Formulaire de connexion -->
    <div class="container my-5" style="max-width: 500px;">
        <h2>Connexion</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Email :</label>
                <input type="email" name="email" class="form-control" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            </div>
            <div class="mb-3">
                <label>Mot de passe :</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
