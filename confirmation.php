<?php
session_start();
require 'config.php';

/* 🔐 Vérification connexion */
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion_commander.php");
    exit;
}

$id_utilisateur = (int)$_SESSION['user_id'];

/* 🔎 Récupération des données POST */
$nb_personnes = (int)($_POST['nb_personnes'] ?? 1);
$adresse_prestation = $_POST['adresse'] ?? '';
$ville = $_POST['ville'] ?? '';
$lieu = $_POST['lieu'] ?? '';
$date_prestation = $_POST['date_prestation'] ?? date('Y-m-d');
$heure_prestation = $_POST['heure_prestation'] ?? date('H:i');

/* Récupération infos utilisateur */
$stmtUser = $pdo->prepare("SELECT nom, prenom, email, gsm FROM utilisateur WHERE id = ?");
$stmtUser->execute([$id_utilisateur]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);
if (!$user) exit("Utilisateur introuvable");

/* Récupération du menu choisi */
$id_menu = (int)($_POST['id_menu'] ?? 0);
if ($id_menu <= 0) exit("Menu introuvable");

/* Récupérer le menu réel depuis la base (si disponible) */
$stmtMenu = $pdo->prepare("SELECT titre, description, prix FROM menus WHERE id = ?");
$stmtMenu->execute([$id_menu]);
$menu = $stmtMenu->fetch(PDO::FETCH_ASSOC);
if (!$menu) exit("Menu introuvable");

/* Calcul prix */
$prix_menu_par_personne = (float)$menu['prix'];
$prix_menu = $nb_personnes * $prix_menu_par_personne;
$prix_livraison = strtolower($ville) === 'bordeaux' ? 0 : 5;
$remise = 0;
$prix_total = $prix_menu + $prix_livraison - $remise;

/* Insertion commande */
$stmt = $pdo->prepare("
    INSERT INTO commande
    (id_utilisateur, id_menu, nom, prenom, email, gsm, adresse_prestation, ville, lieu, nb_personnes, prix_menu, prix_livraison, remise, prix_total, statut, date_creation)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'En attente', NOW())
");
$stmt->execute([
    $id_utilisateur,
    $id_menu,
    $user['nom'],
    $user['prenom'],
    $user['email'],
    $user['gsm'],
    $adresse_prestation,
    $ville,
    $lieu,
    $nb_personnes,
    $prix_menu,
    $prix_livraison,
    $remise,
    $prix_total
]);

/* Récupération de l'ID de la commande insérée */
$id_commande = $pdo->lastInsertId();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Confirmation de commande – Vite & Gourmand</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f0f8f0; font-family: Arial, sans-serif; }
.card-success { background-color: #c0eac0; padding: 1rem; border-radius: 12px; max-width: 500px; margin: auto; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.card-success h2 { text-align: center; margin-bottom: 0.5rem; }
.card-success p { margin: 0.2rem 0; }
.btn-success-full { background-color: #4CAF50; color: white; border: none; width: 100%; padding: 0.7rem; margin-top: 1rem; border-radius: 6px; text-transform: uppercase; font-weight: 600; }
.btn-success-full:hover { background-color: #399139; }
</style>
</head>
<body>

<div class="container my-5">
    <div class="card card-success text-center">
        <h2>Merci pour votre commande !</h2>

        <h4>Détail du menu</h4>
        <p><?= htmlspecialchars($menu['titre']) ?></p>
        <p><?= htmlspecialchars($menu['description']) ?></p>
        <p><strong>Nombre de personnes :</strong> <?= $nb_personnes ?></p>
        <p><strong>Prix du menu :</strong> <?= number_format($prix_menu,2,',',' ') ?> €</p>
        <p><strong>Frais livraison :</strong> <?= number_format($prix_livraison,2,',',' ') ?> €</p>
        <?php if($remise>0): ?>
        <p><strong>Remise :</strong> <?= number_format($remise,2,',',' ') ?> €</p>
        <?php endif; ?>
        <p><strong>Total :</strong> <?= number_format($prix_total,2,',',' ') ?> €</p>

        <a href="mes_commandes.php" class="btn btn-success-full mt-3">Voir ma commande</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
