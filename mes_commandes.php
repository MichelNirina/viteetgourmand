<?php
session_start();
require 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'mes_commandes.php';
    header("Location: connexion_commander.php");
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

/* 🔹 Récupérer le dernier statut depuis statut_historique ou 'En attente' par défaut */
function dernier_statut($pdo, $id_commande) {
    $stmt = $pdo->prepare("
        SELECT statut 
        FROM statut_historique 
        WHERE id_commande = ? 
        ORDER BY date_modification DESC 
        LIMIT 1
    ");
    $stmt->execute([$id_commande]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['statut'] ?? 'En attente';
}

function normaliser_statut($statut) {
    $statut = strtolower($statut ?? 'en attente');
    $statut = str_replace(['é','è','ê','ë'], 'e', $statut);
    return trim($statut);
}

function label_statut($statut) {
    return $statut ?? 'En attente';
}

/* 🔹 Annulation de commande */
if (isset($_GET['annuler'])) {
    $id_annuler = (int)$_GET['annuler'];
    $statut_actuel = dernier_statut($pdo, $id_annuler);
    if (normaliser_statut($statut_actuel) === 'en attente') {
        $stmtDel = $pdo->prepare("DELETE FROM commande WHERE id = ?");
        $stmtDel->execute([$id_annuler]);
        header("Location: mes_commandes.php");
        exit;
    }
}

/* 🔹 Récupération commandes */
$stmt = $pdo->prepare("
    SELECT c.id, c.nb_personnes, c.id_menu, c.prix_total, c.date_creation, m.titre AS menu_titre
    FROM commande c
    LEFT JOIN menus m ON c.id_menu = m.id
    WHERE c.id_utilisateur = ?
    ORDER BY c.date_creation DESC
");
$stmt->execute([$id_utilisateur]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mes commandes – Vite & Gourmand</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.header-link { transition: color 0.3s; color: black; }
.header-link:hover { color: #007bff; }
.btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
.btn-hover-green:hover { color: white !important; background-color: #28a745 !important; }
.navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }
</style>
</head>
<body>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
       <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item mx-2"><a class="nav-link header-link" href="index.php">Accueil</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="mes_menus.php">Mes Menus</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="mes_commandes.php">Mes Commandes</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="contact.php">Contact</a></li>
                <li class="nav-item mx-2">
                    <a class="nav-link btn btn-danger btn-hover-red px-3" href="deconnexion.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENU -->
<div class="container my-5">
<h1 class="mb-4 text-center">Vos commandes</h1>

<?php if (empty($commandes)): ?>
    <p class="text-center">Vous n'avez pas encore de commandes.</p>
<?php else: ?>

<!-- Desktop -->
<div class="table-responsive d-none d-md-block">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Menu</th>
            <th>Personnes</th>
            <th>Prix</th>
            <th>Statut</th>
            <th>Date de commandes</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($commandes as $cmd):
        $statut = dernier_statut($pdo, $cmd['id']);
        $statut_normal = normaliser_statut($statut);

        $date = date('d.m.Y', strtotime($cmd['date_creation']));
        $heure = date('H:i', strtotime($cmd['date_creation']));

        // Définir les boutons selon le statut
        $boutons = ['<a href="detail_commande.php?id='.$cmd['id'].'" class="btn btn-primary btn-sm mb-1">Voir détail</a>'];
        if ($statut_normal === 'en attente') {
            $boutons[] = '<a href="?annuler='.$cmd['id'].'" class="btn btn-danger btn-sm mb-1" onclick="return confirm(\'Annuler cette commande ?\')">Annuler</a>';
        } elseif ($statut_normal === 'livre') {
            $boutons[] = '<a href="donner_avis.php?id='.$cmd['id'].'" class="btn btn-success btn-sm mb-1">Donner avis</a>';
        }
        $boutons = array_slice($boutons, 0, 2); // max 2 boutons
    ?>
        <tr>
            <td><?= htmlspecialchars($cmd['menu_titre'] ?? 'Menu non précisé') ?></td>
            <td><?= $cmd['nb_personnes'] ?></td>
            <td><?= number_format($cmd['prix_total'] ?? 0,2,',',' ') ?> €</td>
            <td><?= htmlspecialchars(label_statut($statut)) ?></td>
            <td><?= $date ?> à <?= $heure ?></td>
            <td class="d-flex flex-wrap gap-1">
                <?php foreach($boutons as $b) echo $b.' '; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<!-- Mobile -->
<div class="d-md-none">
    <?php foreach ($commandes as $cmd):
        $statut = dernier_statut($pdo, $cmd['id']);
        $statut_normal = normaliser_statut($statut);

        $date = date('d.m.Y', strtotime($cmd['date_creation']));
        $heure = date('H:i', strtotime($cmd['date_creation']));

        $boutons = ['<a href="detail_commande.php?id='.$cmd['id'].'" class="btn btn-primary w-100 mb-1">Voir détail</a>'];
        if ($statut_normal === 'en attente') {
            $boutons[] = '<a href="?annuler='.$cmd['id'].'" class="btn btn-danger w-100 mb-1" onclick="return confirm(\'Annuler cette commande ?\')">Annuler</a>';
        } elseif ($statut_normal === 'livre') {
            $boutons[] = '<a href="donner_avis.php?id='.$cmd['id'].'" class="btn btn-success w-100 mb-1">Donner avis</a>';
        }
        $boutons = array_slice($boutons, 0, 2);
    ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex flex-column gap-2">
                <h5><?= htmlspecialchars($cmd['menu_titre'] ?? 'Menu non précisé') ?></h5>
                <p>Personnes : <?= $cmd['nb_personnes'] ?></p>
                <p>Prix : <?= number_format($cmd['prix_total'] ?? 0,2,',',' ') ?> €</p>
                <p>Statut : <strong><?= htmlspecialchars(label_statut($statut)) ?></strong></p>
                <p>Date de commandes: <?= $date ?> à <?= $heure ?></p>
                <?php foreach($boutons as $b) echo $b.' '; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="bg-light p-4 mt-5 text-center">
    <p>Horaires : Lundi-Dimanche 9h-19h | Mentions légales | CGV</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
