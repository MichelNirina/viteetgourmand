<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur est connecté
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
    <title>Espace Utilisateur - Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1>Bienvenue <?= htmlspecialchars($_SESSION['user_prenom']) ?> <?= htmlspecialchars($_SESSION['user_nom']) ?></h1>
    <p>Voici vos commandes :</p>

    <?php if (count($commandes) === 0): ?>
        <p>Aucune commande pour le moment.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Menu</th>
                    <th>Nombre de personnes</th>
                    <th>Prix total</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($commandes as $commande): ?>
                    <tr>
                        <td><?= $commande['id'] ?></td>
                        <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                        <td><?= $commande['nb_personnes'] ?></td>
                        <td><?= number_format($commande['prix_total'], 2) ?> €</td>
                        <td><?= $commande['statut'] ?></td>
                        <td><?= $commande['date_modification'] ?></td>
                        <td>
    <?php if ($commande['statut'] !== 'accepte'): ?>
        <a href="annuler_commande.php?id=<?= $commande['id'] ?>" class="btn btn-warning btn-sm" onclick="return confirm('Voulez-vous vraiment annuler cette commande ?');">Annuler</a>
    <?php else: ?>
        <span class="text-muted">Non modifiable</span>
    <?php endif; ?>
</td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="deconnexion.php" class="btn btn-danger">Se déconnecter</a>
</div>
</body>
</html>
