<?php
session_start();
include "config.php";

// Récupérer tous les menus
$stmt = $pdo->query("SELECT * FROM Menu");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- IMPORTANT pour mobile -->
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-hover-white {
            color: black !important;
        }
        .btn-hover-white:hover {
            color: white !important;
        }

        /* Card height flexible pour mobile */
        .card h5 {
            font-size: 1.2rem;
        }

        .card-text {
            font-size: 0.95rem;
        }

        footer p {
            font-size: 0.9rem;
            margin: 0;
        }

        /* Pour que les boutons du header s'affichent bien sur petit écran */
        @media (max-width: 576px) {
            .navbar .btn {
                width: 100%;
                margin-top: 5px;
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>

        <!-- Bouton toggle pour mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#menus">Menus</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary btn-hover-white ms-2 px-3" href="mes_commandes.php">
                            Mes commandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger btn-hover-white ms-2 px-3" href="deconnexion.php">
                            Déconnexion
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
<section class="container my-5">
    <h1 class="text-center">Bienvenue chez Vite & Gourmand</h1>
    <p class="text-center">Nous proposons des menus pour tous vos événements : Noël, Pâques, et autres occasions.</p>
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
                            Prix : <?= htmlspecialchars($menu['prix_min']); ?> €<br>
                            Pour : <?= htmlspecialchars($menu['nb_min_personnes']); ?> personnes
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
