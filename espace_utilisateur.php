<?php
session_start();
require 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Récupérer les commandes de l'utilisateur
$stmt = $pdo->prepare("
    SELECT c.*, m.titre AS menu_titre
    FROM Commande c
    JOIN Menu m ON c.id_menu = m.id
    WHERE c.id_utilisateur = ?
    ORDER BY c.date_modification DESC
");
$stmt->execute([$_SESSION['user_id']]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Utilisateur - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
        <a href="deconnexion.php" class="btn btn-danger btn-sm">Déconnexion</a>
    </div>
</nav>

<div class="container my-4">

    <h2 class="mb-3">
        👋 Bienvenue <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
    </h2>

    <?php if (empty($commandes)): ?>
        <div class="alert alert-info">
            Vous n'avez encore passé aucune commande.
        </div>
    <?php endif; ?>

    <!-- ===================== -->
    <!-- VERSION MOBILE (CARDS) -->
    <!-- ===================== -->
    <div class="d-block d-md-none">
        <?php foreach ($commandes as $commande): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($commande['menu_titre']) ?></h5>

                    <p class="mb-1"><strong>Personnes :</strong> <?= $commande['nb_personnes'] ?></p>
                    <p class="mb-1"><strong>Prix :</strong> <?= number_format($commande['prix_total'], 2) ?> €</p>
                    <p class="mb-1">
                        <strong>Statut :</strong>
                        <span class="badge bg-secondary"><?= ucfirst($commande['statut']) ?></span>
                    </p>
                    <p class="mb-2"><small><?= $commande['date_modification'] ?></small></p>

                    <?php if ($commande['statut'] !== 'accepte'): ?>
                        <a href="annuler_commande.php?id=<?= $commande['id'] ?>"
                           class="btn btn-warning btn-sm w-100"
                           onclick="return confirm('Annuler cette commande ?');">
                            Annuler la commande
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Commande validée</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ===================== -->
    <!-- VERSION DESKTOP (TABLE) -->
    <!-- ===================== -->
    <div class="d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Menu</th>
                        <th>Personnes</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= $commande['id'] ?></td>
                            <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                            <td><?= $commande['nb_personnes'] ?></td>
                            <td><?= number_format($commande['prix_total'], 2) ?> €</td>
                            <td><?= ucfirst($commande['statut']) ?></td>
                            <td><?= $commande['date_modification'] ?></td>
                            <td>
                                <?php if ($commande['statut'] !== 'accepte'): ?>
                                    <a href="annuler_commande.php?id=<?= $commande['id'] ?>"
                                       class="btn btn-warning btn-sm"
                                       onclick="return confirm('Annuler cette commande ?');">
                                        Annuler
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Non modifiable</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
