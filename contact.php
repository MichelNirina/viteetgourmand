<?php
session_start();
require_once 'config.php';

$success = '';
$error = '';
$recap = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $titre = trim($_POST['titre'] ?? '');
    $messageCont = trim($_POST['message'] ?? '');

    if (!$email || !$titre || !$messageCont) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Veuillez entrer un email valide.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact (email, titre, message, date_creation) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$email, $titre, $messageCont]);
            
            $success = "Votre message a été enregistré avec succès. Merci !";

            // Préparer le récapitulatif
            $recap = [
                'email' => $email,
                'titre' => $titre,
                'message' => $messageCont
            ];

        } catch (PDOException $e) {
            $error = "Erreur lors de l'enregistrement. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Contact – Vite & Gourmand</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.header-link { transition: color 0.3s; color: black; }
.header-link:hover { color: #007bff; }
.btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
.user-name { font-size: 0.75rem; line-height: 1rem; display: block; }
.navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }
.card-custom { max-width: 700px; width: 100%; padding: 30px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
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
                        <a class="nav-link btn btn-danger btn-hover-red px-3" href="deconnexion.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-success btn-hover-white px-3" href="connexion.php">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5 d-flex justify-content-center">
    <div class="card p-4 shadow" style="width:100%; max-width:500px;">
        <h2 class="mb-4 text-center">Contactez-nous</h2>

        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if($success && $recap): ?>
            <div class="mb-3">
                <h5>Récapitulatif de votre message :</h5>
                <p><strong>Email :</strong> <?= htmlspecialchars($recap['email']) ?></p>
                <p><strong>Titre :</strong> <?= htmlspecialchars($recap['titre']) ?></p>
                <p><strong>Message :</strong><br><?= nl2br(htmlspecialchars($recap['message'])) ?></p>
            </div>
        <?php endif; ?>

        <?php if(!$success): ?>
        <form method="POST" class="row g-3">
            <div class="col-12">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="col-12">
                <label>Titre</label>
                <input type="text" name="titre" class="form-control" required value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">
            </div>

            <div class="col-12">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>

            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-primary w-100">Envoyer</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<footer class="bg-light p-4 mt-5 text-center">
    Horaires : Lundi-Dimanche 9h-19h | Mentions légales | CGV
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
