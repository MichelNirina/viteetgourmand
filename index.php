<?php
session_start(); // Démarrage de la session pour vérifier si l'utilisateur est connecté
include "config.php";

// Récupérer tous les menus
$stmt = $pdo->query("SELECT * FROM Menu");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Boutons du header : texte noir par défaut, blanc au survol */
        .btn-hover-white {
            color: black !important;
        }
        .btn-hover-white:hover {
            color: white !important;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Vite & Gourmand</a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                    <?php if(isset($_SESSION['user_id'])): ?>

                        <!-- Bouton Mes commandes -->
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-hover-white ms-2 px-3" 
                               href="mes_commandes.php">
                               Mes commandes
                            </a>
                        </li>

                        <!-- Bouton Déconnexion -->
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger btn-hover-white ms-2 px-3"
                               href="deconnexion.php">
                               Déconnexion
                            </a>
                        </li>

                    <?php else: ?>

                        <!-- Bouton Connexion -->
                        <li class="nav-item">
                            <a class="nav-link btn btn-success btn-hover-white ms-2 px-3" 
                               href="connexion.php">
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
        <h1>Bienvenue chez Vite & Gourmand</h1>
        <p>Nous proposons des menus pour tous vos événements : Noël, Pâques, et autres occasions.</p>
    </section>

    <!-- Section menus -->
    <section class="container my-5" id="menus">
        <h2>Nos Menus</h2>

        <?php if (count($menus) == 0): ?>
            <p class="text-danger">Aucun menu disponible pour le moment.</p>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($menu['titre']); ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($menu['description'])); ?></p>
                            <p>
                                Prix : <?= htmlspecialchars($menu['prix_min']); ?> €<br>
                                Pour : <?= htmlspecialchars($menu['nb_min_personnes']); ?> personnes
                            </p>
                            <a href="detail_menu.php?id=<?= $menu['id']; ?>" class="btn btn-primary">
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
