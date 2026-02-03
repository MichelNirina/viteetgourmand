<?php
session_start();
require 'config.php';

/* =========================
   1. Sécurité : utilisateur connecté
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

/* =========================
   2. Vérification ID menu
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Aucun menu sélectionné.");
}

$id_menu = (int) $_GET['id'];

/* =========================
   3. Charger le menu
========================= */
$stmt = $pdo->prepare("SELECT * FROM Menu WHERE id = ?");
$stmt->execute([$id_menu]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("❌ Menu introuvable.");
}

$message = "";

/* =========================
   4. Traitement du formulaire
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom           = trim($_POST["nom"]);
    $prenom        = trim($_POST["prenom"]);
    $email         = trim($_POST["email"]);
    $adresse       = trim($_POST["adresse"]);
    $gsm           = trim($_POST["gsm"]);
    $nb_personnes  = (int) $_POST["nb_personnes"];
    $id_utilisateur = $_SESSION['user_id'];

    /* ---- Calcul du prix ---- */
    $prix_min = (float) $menu['prix_minimum'];
    $nb_min   = (int) $menu['nb_personnes_min'];

    if ($nb_personnes < $nb_min) {
        $message = "❌ Le nombre minimum de personnes est $nb_min.";
    } else {

        $prix_total = ($nb_personnes / $nb_min) * $prix_min;

        /* ---- Insertion commande ---- */
        $stmt = $pdo->prepare("
            INSERT INTO Commande
            (id_utilisateur, nom, prenom, email, adresse, gsm, id_menu, nb_personnes, prix_total, statut)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'recu')
        ");

        $ok = $stmt->execute([
            $id_utilisateur,
            $nom,
            $prenom,
            $email,
            $adresse,
            $gsm,
            $id_menu,
            $nb_personnes,
            $prix_total
        ]);

        if ($ok) {
            $id_commande = $pdo->lastInsertId();
            header("Location: confirmation_commande.php?id=" . $id_commande);
            exit;
        } else {
            $message = "❌ Erreur lors de l'enregistrement de la commande.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commander : <?= htmlspecialchars($menu['titre']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; }
        .btn-black { background: #000; color: #fff; }
        .btn-black:hover { background: #222; color: #fff; }
    </style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Vite & Gourmand</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#menus">Menus</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item">
                    <a class="btn btn-black" href="mes_commandes.php">Mes commandes</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger" href="deconnexion.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== FORMULAIRE ===== -->
<div class="container d-flex justify-content-center my-5">
    <div class="card shadow p-4 w-100" style="max-width: 600px;">

        <h2 class="text-center mb-4">
            Commander : <?= htmlspecialchars($menu['titre']) ?>
        </h2>

        <?php if ($message): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">GSM</label>
                <input type="text" name="gsm" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Nombre de personnes (min <?= $menu['nb_personnes_min'] ?>)
                </label>
                <input type="number"
                       name="nb_personnes"
                       class="form-control"
                       min="<?= $menu['nb_personnes_min'] ?>"
                       value="<?= $menu['nb_personnes_min'] ?>"
                       required>
            </div>

            <p class="fw-bold">
                Prix minimum :
                <?= number_format($menu['prix_minimum'], 2, ',', ' ') ?> €
            </p>

            <button type="submit" class="btn btn-success w-100 mt-3">
                ✅ Valider la commande
            </button>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
