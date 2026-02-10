<?php
session_start();
require 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'detail_commande.php?id=' . ($_GET['id'] ?? '');
    header("Location: connexion.php");
    exit;
}

$id_utilisateur = (int)$_SESSION['user_id'];

// Vérifier qu'un ID de commande est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Commande introuvable.");
}

$id_commande = (int)$_GET['id'];

// Récupérer la commande et le menu associé
$stmt = $pdo->prepare("
    SELECT c.id, c.nb_personnes, c.date_creation, m.titre AS menu_titre, m.description AS menu_description, m.prix AS prix_unitaire
    FROM commande c
    JOIN menus m ON c.id_menu = m.id
    WHERE c.id = ? AND c.id_utilisateur = ?
");
$stmt->execute([$id_commande, $id_utilisateur]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("Commande introuvable ou vous n'avez pas accès à cette commande.");
}

// Fonction pour récupérer le dernier statut
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
    return $res ? $res['statut'] : 'Inconnu';
}

$statut = dernier_statut($pdo, $id_commande);
$prix_total = (float)$commande['nb_personnes'] * (float)$commande['prix_unitaire'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Détails de la commande – Vite & Gourmand</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.header-link { transition: color 0.3s; color: black; }
.header-link:hover { color: #007bff; }
.btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
.navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }
</style>
</head>
<body>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
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

<!-- DÉTAIL DE COMMANDE -->
<div class="container d-flex justify-content-center my-5">
    <div class="card p-4 shadow" style="width: 100%; max-width: 500px;">
        <h2 class="mb-4 text-center">Détails de la commande</h2>
        <p><strong>Menu :</strong> <?= htmlspecialchars($commande['menu_titre']) ?></p>
        <?php if (!empty($commande['menu_description'])): ?>
            <p><strong>Description :</strong> <?= htmlspecialchars($commande['menu_description']) ?></p>
        <?php endif; ?>
        <p><strong>Nombre de personnes :</strong> <?= (int)$commande['nb_personnes'] ?></p>
        <p><strong>Prix unitaire :</strong> <?= number_format((float)$commande['prix_unitaire'],2,',',' ') ?> €</p>
        <p><strong>Prix total :</strong> <?= number_format($prix_total,2,',',' ') ?> €</p>
        <p><strong>Date de commande :</strong> <?= htmlspecialchars($commande['date_creation']) ?></p>
        <p><strong>Statut :</strong> <?= htmlspecialchars($statut) ?></p>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-light p-4 mt-5">
    <p style="text-align:center; margin:0;">Horaires : Lundi-Dimanche 9h-19h | Mentions légales | CGV</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
