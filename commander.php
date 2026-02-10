<?php
session_start();
require_once 'config.php';

/* 🔐 Vérification connexion */
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion_commander.php?redirect=commander.php?id_menu=" . ($_GET['id_menu'] ?? ''));
    exit;
}

/* 🔎 Vérification menu */
if (!isset($_GET['id_menu']) || !is_numeric($_GET['id_menu'])) {
    exit("❌ Menu introuvable");
}

$id_menu = (int)$_GET['id_menu'];

/* 📦 Menu */
$stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
$stmt->execute([$id_menu]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$menu) exit("❌ Menu introuvable");

/* 👤 Utilisateur */
$stmtUser = $pdo->prepare("SELECT nom, prenom, email, gsm FROM utilisateur WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

/* Variable utilisateur pour header */
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Commander – <?= htmlspecialchars($menu['titre']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* Header */
.header-link { transition: color 0.3s; color: black; }
.header-link:hover { color: #007bff; }
.btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
.btn-hover-green:hover { color: white !important; background-color: #28a745 !important; }
.navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }
.card { border-radius: 12px; }
</style>
</head>

<body>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Vite & Gourmand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item"><a class="nav-link header-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link header-link" href="mes_menus.php">Mes Menus</a></li>
                <li class="nav-item"><a class="nav-link header-link" href="mes_commandes.php">Mes Commandes</a></li>
                <li class="nav-item"><a class="nav-link header-link" href="contact.php">Contact</a></li>

                <?php if($user_id): ?>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger btn-hover-red px-3" href="deconnexion.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-success btn-hover-green px-3" href="connexion.php">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENU -->
<div class="container my-5 d-flex justify-content-center">
<div class="card shadow-sm p-4" style="max-width:720px;width:100%;">

<h2 class="mb-4 text-center">Commande du menu</h2>

<form method="post" action="confirmation.php" class="row g-3">

<!-- CLIENT -->
<h5>Informations client</h5>
<div class="col-md-6"><label>Nom</label><input class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" readonly></div>
<div class="col-md-6"><label>Prénom</label><input class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" readonly></div>
<div class="col-md-6"><label>Email</label><input class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly></div>
<div class="col-md-6"><label>GSM</label><input class="form-control" value="<?= htmlspecialchars($user['gsm']) ?>" readonly></div>

<!-- PRESTATION -->
<h5 class="mt-4">Prestation</h5>
<div class="col-12"><label>Adresse</label><input name="adresse" class="form-control" required></div>
<div class="col-md-6"><label>Ville</label><input name="ville" class="form-control" required></div>
<div class="col-12"><label>Lieu précis</label><input name="lieu" class="form-control" required></div>

<!-- MENU -->
<h5 class="mt-4">Menu choisi</h5>
<div class="col-12">
    <input type="hidden" name="id_menu" value="<?= $menu['id'] ?>">
    <p class="fw-bold"><?= htmlspecialchars($menu['titre']) ?></p>
    <p><?= nl2br(htmlspecialchars($menu['description'])) ?></p>
</div>

<div class="col-md-6">
<label>Nombre de personnes</label>
<input type="number" id="nb_personnes" name="nb_personnes"
       class="form-control"
       min="<?= $menu['nombre_personnes_min'] ?>"
       value="<?= $menu['nombre_personnes_min'] ?>" required>
</div>

<hr class="mt-4">

<!-- RÉCAP PRIX -->
<div class="col-12">
<p>Menu : <strong><span id="prix_menu"></span> €</strong></p>
<p>Livraison : <strong><span id="prix_livraison"></span> €</strong></p>
<p class="fs-5">Total : <strong><span id="total"></span> €</strong></p>
</div>

<input type="hidden" name="prix_menu" id="hidden_prix_menu">
<input type="hidden" name="prix_livraison" id="hidden_prix_livraison">

<div class="col-12 text-center mt-4">
<button class="btn btn-primary w-100">Valider la commande</button>
</div>

</form>
</div>
</div>

<script>
const prixPers = <?= (float)$menu['prix'] ?>;
const minPers = <?= (int)$menu['nombre_personnes_min'] ?>;
const livraisonFixe = 5;

const nb = document.getElementById('nb_personnes');
const ville = document.querySelector('input[name="ville"]');

function calcul() {
    let personnes = Math.max(parseInt(nb.value), minPers);
    let menu = personnes * prixPers;
    let livraison = ville.value.trim().toLowerCase() === 'bordeaux' ? 0 : livraisonFixe;

    document.getElementById('prix_menu').textContent = menu.toFixed(2);
    document.getElementById('prix_livraison').textContent = livraison.toFixed(2);
    document.getElementById('total').textContent = (menu + livraison).toFixed(2);

    document.getElementById('hidden_prix_menu').value = menu;
    document.getElementById('hidden_prix_livraison').value = livraison;
}

nb.addEventListener('input', calcul);
ville.addEventListener('input', calcul);
calcul();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
