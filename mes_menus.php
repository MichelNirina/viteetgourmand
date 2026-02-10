<?php
session_start();
include __DIR__ . '/config.php';

/* ===== Utilisateur connecté ===== */
$prenom = null;
$nom = null;
if (isset($_SESSION['user_id'])) {
    $stmtUser = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE id = ?");
    $stmtUser->execute([$_SESSION['user_id']]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $prenom = ucfirst(substr($user['prenom'], 0, 1)) . ".";
        $nom = $user['nom'];
    }
}

/* ===== Filtres ===== */
$prix_min = isset($_GET['prix_min']) ? (int)$_GET['prix_min'] : 0;
$prix_max = isset($_GET['prix_max']) ? (int)$_GET['prix_max'] : 1000;
$theme = $_GET['theme'] ?? '';
$regime = $_GET['regime'] ?? '';
$personnes_min = isset($_GET['personnes_min']) ? (int)$_GET['personnes_min'] : 1;

/* ===== Requête ===== */
$query = "SELECT * FROM menus WHERE prix >= ? AND prix <= ? AND nombre_personnes_min >= ?";
$params = [$prix_min, $prix_max, $personnes_min];

if ($theme && $theme !== 'Tous') {
    $query .= " AND theme = ?";
    $params[] = $theme;
}
if ($regime && $regime !== 'Tous') {
    $query .= " AND regime = ?";
    $params[] = $regime;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nos Menus - Vite & Gourmand</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ===== HEADER ===== */
.header-link { transition: color 0.3s; color: black; }
.header-link:hover { color: #007bff; }
.btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
.navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }

/* ===== MENUS ===== */
.card-img-top { height: 200px; object-fit: cover; }
.card-body { display: flex; flex-direction: column; }
.card-text { flex-grow: 1; }

/* Limite hauteur description */
.menu-description-preview { min-height: 60px; }

/* Boutons */
.btn { margin-top: 5px; }

/* ===== FOOTER ===== */
footer p { font-size: 0.9rem; margin: 0; text-align: center; }
</style>
</head>

<body>

<!-- Header -->
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

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-danger btn-hover-red px-3" href="deconnexion.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-success px-3" href="connexion.php">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== CONTENU ===== -->
<div class="container my-5">
<h1 class="mb-4">Nos Menus</h1>

<!-- FILTRES -->
<form id="filtersForm" class="row g-3 mb-4">
    <div class="col-md-2">
        <label class="form-label">Prix min</label>
        <input type="number" name="prix_min" class="form-control" value="<?= $prix_min ?>">
    </div>
    <div class="col-md-2">
        <label class="form-label">Prix max</label>
        <input type="number" name="prix_max" class="form-control" value="<?= $prix_max ?>">
    </div>
    <div class="col-md-2">
        <label class="form-label">Thème</label>
        <select name="theme" class="form-select">
            <option value="Tous">Tous</option>
            <option value="Noel" <?= $theme=='Noel'?'selected':'' ?>>Noël</option>
            <option value="Paques" <?= $theme=='Paques'?'selected':'' ?>>Pâques</option>
            <option value="Classique" <?= $theme=='Classique'?'selected':'' ?>>Classique</option>
            <option value="Evenement" <?= $theme=='Evenement'?'selected':'' ?>>Événement</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Régime</label>
        <select name="regime" class="form-select">
            <option value="Tous">Tous</option>
            <option value="Classique" <?= $regime=='Classique'?'selected':'' ?>>Classique</option>
            <option value="Vegetarien" <?= $regime=='Vegetarien'?'selected':'' ?>>Végétarien</option>
            <option value="Vegan" <?= $regime=='Vegan'?'selected':'' ?>>Vegan</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Personnes min</label>
        <input type="number" name="personnes_min" class="form-control" value="<?= $personnes_min ?>">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100">Filtrer</button>
    </div>
</form>

<!-- MENUS -->
<div class="row" id="menusContainer">
<?php foreach($menus as $menu): ?>
<div class="col-md-6 col-lg-4 mb-4">
<div class="card shadow-sm h-100">

<img src="<?= htmlspecialchars($menu['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['titre']) ?>">

<div class="card-body d-flex flex-column">

    <h5><?= htmlspecialchars($menu['titre']) ?></h5>

    <?php
    $desc_preview = strip_tags($menu['description']);
    if (strlen($desc_preview) > 120) {
        $desc_preview = substr($desc_preview, 0, 117) . '...';
    }
    ?>
    <p class="card-text menu-description-preview"><?= nl2br(htmlspecialchars($desc_preview)) ?></p>

    <p class="card-text">
        <strong>Thème :</strong> <?= htmlspecialchars($menu['theme']) ?><br>
        <strong>Régime :</strong> <?= htmlspecialchars($menu['regime']) ?><br>
        <strong>Personnes min :</strong> <?= $menu['nombre_personnes_min'] ?><br>
        <strong>Prix :</strong> <?= $menu['prix'] ?> €
    </p>

    <a href="detail_menu.php?id_menu=<?= $menu['id'] ?>" class="btn btn-outline-primary w-100 mb-2">Voir le détail</a>
    <a href="commander.php?id_menu=<?= $menu['id'] ?>" class="btn btn-success w-100 mt-auto">Commander</a>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- FOOTER -->
<footer class="bg-light p-4 mt-5">
    <p>Horaires : Lundi-Dimanche 9h-19h | Mentions légales | CGV</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
