<?php
session_start();
require 'config.php';

// Vérifier l'ID du menu
if (!isset($_GET['id_menu']) || !is_numeric($_GET['id_menu'])) {
    exit("❌ Menu introuvable");
}

$id_menu = (int)$_GET['id_menu'];

// Récupérer le menu
$stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
$stmt->execute([$id_menu]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    exit("❌ Menu introuvable");
}

// Infos utilisateur pour header
$prenom = null;
$nom = null;
if (isset($_SESSION['user_id'])) {
    $stmtUser = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE id = ?");
    $stmtUser->execute([$_SESSION['user_id']]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $prenom = ucfirst(substr($user['prenom'],0,1)).".";
        $nom = $user['nom'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Détail du menu - <?= htmlspecialchars($menu['titre']) ?> | Vite & Gourmand</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Header */
.header-link { transition: color 0.3s; color: black; }
.header-link:hover { color: #007bff; }
.btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
.navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }

/* Card centrée */
.card-detail { max-width: 800px; width: 100%; }

/* Image */
.card-img-top { height: 400px; object-fit: cover; }

/* Footer */
footer p { font-size: 0.9rem; margin: 0; text-align: center; }
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
                        <a class="nav-link btn btn-danger btn-hover-red px-3" href="deconnexion.php">
                            Déconnexion
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-success btn-hover-white px-3" href="connexion_commander.php">
                            Connexion
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu Détail Menu -->
<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow-sm card-detail">
        <?php if (!empty($menu['image'])): ?>
            <img src="<?= htmlspecialchars($menu['image']) ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($menu['titre']) ?>">
        <?php endif; ?>

        <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($menu['titre']) ?></h2>

            <?php if (!empty($menu['description'])): ?>
                <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($menu['description'])) ?></p>
            <?php endif; ?>

            <?php if (!empty($menu['conditions'])): ?>
                <p><strong>Conditions :</strong><br><?= nl2br(htmlspecialchars($menu['conditions'])) ?></p>
            <?php endif; ?>

            <p>
                <?php if (!empty($menu['theme'])): ?>
                    <strong>Thème :</strong> <?= htmlspecialchars($menu['theme']) ?><br>
                <?php endif; ?>

                <?php if (!empty($menu['regime'])): ?>
                    <strong>Régime :</strong> <?= htmlspecialchars($menu['regime']) ?><br>
                <?php endif; ?>

                <?php if (!empty($menu['nombre_personnes_min'])): ?>
                    <strong>Personnes minimum :</strong> <?= (int)$menu['nombre_personnes_min'] ?><br>
                <?php endif; ?>

                <?php if (!empty($menu['prix'])): ?>
                    <strong>Prix :</strong> <?= number_format((float)$menu['prix'], 2, ',', ' ') ?> €<br>
                <?php endif; ?>

                <?php if (isset($menu['stock'])): ?>
                    <strong>Stock :</strong> <?= (int)$menu['stock'] ?>
                <?php endif; ?>
            </p>

            <a href="commander.php?id_menu=<?= (int)$menu['id'] ?>" class="btn btn-success w-100 mt-3">
                Commander ce menu
            </a>
        </div> <!-- /card-body -->
    </div> <!-- /card -->
</div> <!-- /container -->

<!-- Footer -->
<footer class="bg-light p-4 mt-5">
    <p>Horaires : Lundi-Dimanche 9h-19h | Mentions légales | CGV</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
