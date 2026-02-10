<?php
session_start();
require 'config.php';

/* 🔁 Redirection demandée */
$redirect = $_GET['redirect'] ?? null;

/* 🔐 Déjà connecté → retour immédiat vers commander.php */
if (isset($_SESSION['user_id']) && $redirect) {
    header("Location: " . $redirect);
    exit;
}

$message = "";

/* 📩 Traitement formulaire */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        $message = "❌ Veuillez remplir tous les champs.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {

            /* ✅ Session */
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['prenom']  = $user['prenom'];
            $_SESSION['nom']     = $user['nom'];
            $_SESSION['role']    = $user['role'];

            /* 🔁 REDIRECTION PRIORITAIRE */
            if (!empty($redirect)) {
                header("Location: " . $redirect);
                exit;
            }

            /* 🔁 Redirection classique */
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
            $message = "❌ Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Connexion – Vite & Gourmand</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.header-link { transition: color 0.3s; color: black; }
.header-link:hover { color: #007bff; }
.btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
.navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }
</style>
</head>

<body>

<!-- HEADER ORIGINAL -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item mx-2">
                    <a class="nav-link header-link" href="index.php">Accueil</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link header-link" href="mes_menus.php">Mes Menus</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link header-link" href="mes_commandes.php">Mes Commandes</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link header-link" href="contact.php">Contact</a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-danger btn-hover-red px-3"
                           href="deconnexion.php">
                           Déconnexion
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-success px-3"
                           href="connexion_commander.php">
                           Connexion
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- FORMULAIRE -->
<div class="container d-flex justify-content-center align-items-center my-5">
<div class="card shadow p-4" style="max-width:500px;width:100%;">

<h2 class="mb-4 text-center">Connexion</h2>

<?php if ($message): ?>
<div class="alert alert-danger">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
    <label>Mot de passe</label>
    <input type="password" name="password" class="form-control" required>
</div>

<div class="d-grid">
    <button class="btn btn-primary">
        Se connecter
    </button>
</div>

</form>

<div class="text-center mt-3">
    <p>Pas encore de compte ?
        <a href="inscription.php">Créer un compte</a>
    </p>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
