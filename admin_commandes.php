<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Vous devez être administrateur pour accéder à cette page.");
}

// Récupérer le nom et prénom de l'admin
$stmtAdmin = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE id = ?");
$stmtAdmin->execute([$_SESSION['user_id']]);
$admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

// Tableau des options de statut
$statut_options = ['En attente', 'En préparation', 'Livré', 'Annulé'];

// Mise à jour du statut si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'], $_POST['statut'])) {
    $id_commande = intval($_POST['id_commande']);
    $nouveau_statut = trim($_POST['statut']);
    $date_statut = date('Y-m-d H:i:s');

    // Mettre à jour le statut dans la table commande
    $stmt = $pdo->prepare("UPDATE commande SET statut = ? WHERE id = ?");
    $stmt->execute([$nouveau_statut, $id_commande]);

    // Ajouter une entrée dans l'historique
    $stmtHist = $pdo->prepare("INSERT INTO statut_historique (id_commande, statut, date_modification) VALUES (?, ?, ?)");
    $stmtHist->execute([$id_commande, $nouveau_statut, $date_statut]);

    header("Location: admin_commandes.php");
    exit;
}

// Récupérer toutes les commandes avec infos utilisateur et menu
$commandes = [];
$stmt = $pdo->query("
    SELECT c.*, m.titre AS menu_titre, u.nom, u.prenom
    FROM commande c
    JOIN menus m ON c.id_menu = m.id
    JOIN utilisateur u ON c.id_utilisateur = u.id
    ORDER BY c.date_creation DESC
");
if ($stmt) {
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer le dernier statut historique
function dernier_statut($pdo, $id_commande) {
    $stmt = $pdo->prepare("SELECT statut FROM statut_historique WHERE id_commande = ? ORDER BY date_modification DESC LIMIT 1");
    $stmt->execute([$id_commande]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res ? $res['statut'] : null;
}

// Fonction pour formater date en jj.mm.aaaa hh:mm
function format_date($datetime) {
    $d = new DateTime($datetime);
    return $d->format('d.m.Y H:i');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin - Gestion des commandes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Header Admin -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="admin_dashboard.php">Vite & Gourmand - Admin</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-4">
    <h2 class="mb-4">📦 Commandes clients</h2>

    <?php if (empty($commandes)): ?>
        <div class="alert alert-info">Aucune commande.</div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Menu</th>
                    <th>Pers.</th>
                    <th>Prix</th>
                    <th>Date commande</th>
                    <th>Action</th>
                    <th>Statut actuel</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $c): ?>
                    <?php 
                    $statut_actuel = dernier_statut($pdo, $c['id']) ?? $c['statut']; 
                    $date_formatee = format_date($c['date_creation']);
                    ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
                        <td><?= htmlspecialchars($c['menu_titre']) ?></td>
                        <td><?= $c['nb_personnes'] ?></td>
                        <td><?= number_format($c['prix_total'],2) ?> €</td>
                        <td><?= $date_formatee ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="id_commande" value="<?= $c['id'] ?>">
                                <select name="statut" class="form-select form-select-sm">
                                    <?php
                                    foreach($statut_options as $opt) {
                                        $sel = ($statut_actuel === $opt) ? 'selected' : '';
                                        echo "<option value=\"$opt\" $sel>$opt</option>";
                                    }
                                    ?>
                                </select>
                                <button class="btn btn-sm btn-primary">OK</button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($statut_actuel) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
