<?php
session_start();
require 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

// Récupérer toutes les commandes de l'utilisateur
$stmt = $pdo->prepare("
    SELECT c.id, c.nb_personnes, c.prix_total, c.statut, m.titre AS menu_titre
    FROM Commande c
    JOIN Menu m ON c.id_menu = m.id
    WHERE c.id_utilisateur = ?
    ORDER BY c.id DESC
");
$stmt->execute([$id_utilisateur]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Annulation
if (isset($_GET['annuler'])) {
    $id_annuler = (int)$_GET['annuler'];
    $stmtCheck = $pdo->prepare("SELECT * FROM Commande WHERE id = ? AND id_utilisateur = ?");
    $stmtCheck->execute([$id_annuler, $id_utilisateur]);
    $commande = $stmtCheck->fetch();
    if ($commande) {
        $stmtDel = $pdo->prepare("DELETE FROM Commande WHERE id = ?");
        $stmtDel->execute([$id_annuler]);
        header("Location: mes_commandes.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar responsive -->
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
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="mb-4 text-center">Mes commandes</h1>
    <a href="index.php" class="btn btn-primary mb-3 w-100 w-md-auto">Commander un nouveau menu</a>

    <?php if (empty($commandes)): ?>
        <p class="text-center">Vous n'avez pas encore de commandes.</p>
    <?php else: ?>
        <div class="table-responsive d-none d-md-block">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Nombre de personnes</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($commandes as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['menu_titre']) ?></td>
                            <td><?= $cmd['nb_personnes'] ?></td>
                            <td><?= number_format($cmd['prix_total'], 2) ?> €</td>
                            <td><?= ucfirst($cmd['statut']) ?></td>
                            <td>
                                <?php if ($cmd['statut'] === 'recu'): ?>
                                    <a href="mes_commandes.php?annuler=<?= $cmd['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment annuler cette commande ?')">Annuler</a>
                                <?php endif; ?>
                                <a href="confirmation.php?id=<?= $cmd['id'] ?>" class="btn btn-info btn-sm">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Version mobile : cartes -->
        <div class="d-block d-md-none">
            <?php foreach($commandes as $cmd): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($cmd['menu_titre']) ?></h5>
                        <p>Nombre de personnes : <?= $cmd['nb_personnes'] ?></p>
                        <p>Prix total : <?= number_format($cmd['prix_total'], 2) ?> €</p>
                        <p>Statut : <strong><?= ucfirst($cmd['statut']) ?></strong></p>
                        <div class="d-flex flex-column gap-2">
                            <?php if ($cmd['statut'] === 'recu'): ?>
                                <a href="mes_commandes.php?annuler=<?= $cmd['id'] ?>" class="btn btn-danger w-100" onclick="return confirm('Voulez-vous vraiment annuler cette commande ?')">Annuler</a>
                            <?php endif; ?>
                            <a href="confirmation.php?id=<?= $cmd['id'] ?>" class="btn btn-info w-100">Voir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
