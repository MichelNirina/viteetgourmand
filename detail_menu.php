<?php
include "config.php";

// Vérifier que l'ID du menu est passé en URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Menu non spécifié !");
}

$id_menu = (int) $_GET['id'];

// Infos menu
$stmt = $pdo->prepare("SELECT * FROM Menu WHERE id = ?");
$stmt->execute([$id_menu]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu introuvable !");
}

// Plats du menu
$stmt2 = $pdo->prepare("
    SELECT p.nom, p.type, p.description 
    FROM Plat p
    JOIN Menu_Plat mp ON p.id = mp.id_plat
    WHERE mp.id_menu = ?
");
$stmt2->execute([$id_menu]);
$plats = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- 🔥 IMPORTANT POUR MOBILE -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($menu['titre']) ?> - Vite & Gourmand</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
    </div>
</nav>

<div class="container my-4">

    <!-- TITRE -->
    <h1 class="mb-3"><?= htmlspecialchars($menu['titre']) ?></h1>
    <p class="text-muted"><?= nl2br(htmlspecialchars($menu['description'])) ?></p>

    <!-- INFOS MENU -->
    <div class="row g-3 my-4">
        <div class="col-12 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <p><strong>Thème :</strong> <?= htmlspecialchars($menu['theme']) ?></p>
                    <p><strong>Régime :</strong> <?= htmlspecialchars($menu['regime']) ?></p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <p><strong>Prix minimum :</strong> <?= number_format($menu['prix_minimum'], 2) ?> €</p>
                    <p><strong>Minimum :</strong> <?= $menu['nb_personnes_min'] ?> personnes</p>
                    <p><strong>Stock :</strong> <?= $menu['stock'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONDITIONS -->
    <?php if (!empty($menu['conditions'])): ?>
        <div class="alert alert-warning">
            <strong>Conditions :</strong><br>
            <?= nl2br(htmlspecialchars($menu['conditions'])) ?>
        </div>
    <?php endif; ?>

    <!-- PLATS -->
    <h3 class="mt-4 mb-3">🍽️ Plats inclus</h3>

    <?php if (empty($plats)): ?>
        <p>Aucun plat défini.</p>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($plats as $plat): ?>
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2">
                                <?= ucfirst(htmlspecialchars($plat['type'])) ?>
                            </span>

                            <h5 class="card-title mt-2">
                                <?= htmlspecialchars($plat['nom']) ?>
                            </h5>

                            <p class="card-text">
                                <?= htmlspecialchars($plat['description']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- BOUTON COMMANDER -->
    <div class="d-grid gap-2 my-5">
        <a href="commande.php?id=<?= $menu['id'] ?>" class="btn btn-success btn-lg">
            🛒 Commander ce menu
        </a>
        <a href="index.php#menus" class="btn btn-outline-secondary">
            ⬅ Retour aux menus
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
