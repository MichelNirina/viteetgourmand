<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur est employé ou admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'employe' && $_SESSION['role'] != 'admin')) {
    die("Accès refusé. Vous devez être employé pour accéder à cette page.");
}

// Récupérer toutes les commandes
$stmt = $pdo->query("
    SELECT c.id, c.nom, c.prenom, c.email, c.nb_personnes, c.prix_total, c.id_menu, c.date_creation, c.statut, m.titre AS menu_titre
    FROM Commande c
    JOIN Menu m ON c.id_menu = m.id
    ORDER BY c.date_creation DESC
");
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mettre à jour le statut si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'], $_POST['statut'])) {
    $id_commande = (int)$_POST['id_commande'];
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("UPDATE Commande SET statut = ? WHERE id = ?");
    $stmt->execute([$statut, $id_commande]);

    header("Location: employe_commandes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des commandes - Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1>Gestion des commandes</h1>

    <?php if (count($commandes) === 0): ?>
        <p>Aucune commande pour le moment.</p>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Menu</th>
                    <th>Nb personnes</th>
                    <th>Prix total</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= $commande['id'] ?></td>
                        <td><?= htmlspecialchars($commande['nom']) ?></td>
                        <td><?= htmlspecialchars($commande['prenom']) ?></td>
                        <td><?= htmlspecialchars($commande['email']) ?></td>
                        <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                        <td><?= $commande['nb_personnes'] ?></td>
                        <td><?= number_format($commande['prix_total'], 2) ?> €</td>
                        <td><?= $commande['date_creation'] ?></td>
                        <td><?= $commande['statut'] ?? 'En attente' ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-1">
                                <input type="hidden" name="id_commande" value="<?= $commande['id'] ?>">
                                <select name="statut" class="form-select form-select-sm">
                                    <option value="En attente" <?= ($commande['statut']=='En attente')?'selected':'' ?>>En attente</option>
                                    <option value="En préparation" <?= ($commande['statut']=='En préparation')?'selected':'' ?>>En préparation</option>
                                    <option value="Livré" <?= ($commande['statut']=='Livré')?'selected':'' ?>>Livré</option>
                                    <option value="Annulé" <?= ($commande['statut']=='Annulé')?'selected':'' ?>>Annulé</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                            </form>
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
