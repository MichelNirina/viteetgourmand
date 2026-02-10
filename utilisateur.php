<?php
session_start();
include "config.php";

// Récupérer tous les menus depuis la table "menu"
$stmt = $pdo->query("SELECT * FROM menu ORDER BY id DESC");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur est connecté et récupérer prénom/nom
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmtUser = $pdo->prepare("SELECT prenom, nom FROM utilisateur WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $prenom = $user ? $user['prenom'] : '';
    $nom = $user ? $user['nom'] : '';
} else {
    $prenom = '';
    $nom = '';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-hover-white { color: black !important; }
        .btn-hover-white:hover { color: white !important; }
        .card h5 { font-size: 1.2rem; }
        .card-text { font-size: 0.95rem; }
        footer p { font-size: 0.9rem; margin: 0; }
        @media (max-width: 576px) {
            .navbar .btn { width: 100%; margin-top: 5px; margin-left: 0 !important; }
        }
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
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="#menus">Menus</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary btn-hover-white ms-2 px-3" href="mes_commandes.php">
                            Mes commandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger btn-hover-white ms-2 px-3" href="deconnexion.php">
                            Déconnexion (<?= htmlspecialchars($prenom.' '.$nom) ?>)
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-success btn-hover-white ms-2 px-3" href="connexion.php">
                            Connexion
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Présentation -->
<section class="container my-5 text-center">
    <?php if($prenom && $nom): ?>
        <h4>Bonjour <?= htmlspecialchars($prenom.' '.$nom) ?> 👋</h4>
    <?php endif; ?>
    <h1>Bienvenue chez Vite & Gourmand</h1>
    <p>Nous proposons des menus pour tous vos événements : Noël, Pâques, et autres occasions.</p>
</section>

<!-- Section menus -->
<section class="container my-5" id="menus">
    <h2 class="mb-4 text-center">Nos Menus</h2>

    <?php if (count($menus) == 0): ?>
        <p class="text-danger text-center">Aucun menu disponible pour le moment.</p>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($menus as $menu): ?>
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($menu['titre']); ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($menu['description'])); ?></p>
                        <p class="mt-auto mb-2">
                            Prix : <?= htmlspecialchars($menu['prix_minimum']); ?> €<br>
                            Pour : <?= htmlspecialchars($menu['nb_personnes_min']); ?> personnes
                        </p>
                        <a href="detail_menu.php?id=<?= $menu['id']; ?>" class="btn btn-primary mt-auto">
                            Voir le détail
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Footer -->
<footer class="bg-light text-center p-4">
    <p>Horaires : Lundi-Dimanche 9h-19h | Mentions légales | CGV</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
