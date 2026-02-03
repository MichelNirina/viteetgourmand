<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur est employé ou admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'employe' && $_SESSION['role'] != 'admin')) {
    die("Accès refusé.");
}

// Mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'], $_POST['statut'])) {
    $id_commande = (int)$_POST['id_commande'];
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("UPDATE Commande SET statut = ? WHERE id = ?");
    $stmt->execute([$statut, $id_commande]);

    header("Location: employe_commandes.php");
    exit;
}

// Récupérer toutes les commandes
$stmt = $pdo->query("
    SELECT c.*, m.titre AS menu_titre
    FROM Commande c
    JOIN Menu m ON c.id_menu = m.id
    ORDER BY c.date_creation DESC
");
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">Espace Employé</span>
        <a href="deconnexion.php" class="btn btn-danger btn-sm">Déconnexion</a>
    </div>
</nav>

<div class="container my-4">

    <h2 class="mb-4">📦 Commandes clients</h2>

    <?php if (empty($commandes)): ?>
        <div class="alert alert-info">Aucune commande.</div>
    <?php endif; ?>

    <!-- ================= MOBILE : CARTES ================= -->
    <div class="d-block d-md-none">
        <?php foreach ($commandes as $c): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($c['menu_titre']) ?></h5>

                    <p class="mb-1"><strong>Client :</strong> <?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></p>
                    <p class="mb-1"><strong>Email :</strong> <?= htmlspecialchars($c['email']) ?></p>
                    <p class="mb-1"><strong>Personnes :</strong> <?= $c['nb_personnes'] ?></p>
                    <p class="mb-1"><strong>Prix :</strong> <?= number_format($c['prix_total'],2) ?> €</p>
                    <p class="mb-2"><small><?= $c['date_creation'] ?></small></p>

                    <form method="POST">
                        <input type="hidden" name="id_commande" value="<?= $c['id'] ?>">

                        <select name="statut" class="form-select mb-2">
                            <option value="En attente" <?= $c['statut']=='En attente'?'selected':'' ?>>En attente</option>
                            <option value="En préparation" <?= $c['statut']=='En préparation'?'selected':'' ?>>En préparation</option>
                            <option value="Livré" <?= $c['statut']=='Livré'?'selected':'' ?>>Livré</option>
                            <option value="Annulé" <?= $c['statut']=='Annulé'?'selected':'' ?>>Annulé</option>
                        </select>

                        <button class="btn btn-primary w-100 btn-sm">
                            Mettre à jour
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ================= DESKTOP : TABLE ================= -->
    <div class="d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Menu</th>
                        <th>Pers.</th>
                        <th>Prix</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['menu_titre']) ?></td>
                            <td><?= $c['nb_personnes'] ?></td>
                            <td><?= number_format($c['prix_total'],2) ?> €</td>
                            <td><?= $c['date_creation'] ?></td>
                            <td><?= $c['statut'] ?></td>
                            <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="id_commande" value="<?= $c['id'] ?>">
                                    <select name="statut" class="form-select form-select-sm">
                                        <option value="En attente" <?= $c['statut']=='En attente'?'selected':'' ?>>En attente</option>
                                        <option value="En préparation" <?= $c['statut']=='En préparation'?'selected':'' ?>>En préparation</option>
                                        <option value="Livré" <?= $c['statut']=='Livré'?'selected':'' ?>>Livré</option>
                                        <option value="Annulé" <?= $c['statut']=='Annulé'?'selected':'' ?>>Annulé</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary">OK</button>
                                </form>
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
