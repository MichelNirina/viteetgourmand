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

// Si une commande est annulée via GET
if (isset($_GET['annuler'])) {
    $id_annuler = (int)$_GET['annuler'];
    // Vérifier que la commande appartient bien à l'utilisateur
    $stmtCheck = $pdo->prepare("SELECT * FROM Commande WHERE id = ? AND id_utilisateur = ?");
    $stmtCheck->execute([$id_annuler, $id_utilisateur]);
    $commande = $stmtCheck->fetch();
    if ($commande) {
        // Supprimer la commande ou mettre statut = 'annule'
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
    <title>Mes commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1>Mes commandes</h1>
    <a href="index.php" class="btn btn-primary mb-3">Commander un nouveau menu</a>

    <?php if (empty($commandes)): ?>
        <p>Vous n'avez pas encore de commandes.</p>
    <?php else: ?>
        <table class="table table-bordered">
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
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
