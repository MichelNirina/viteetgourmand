<?php
session_start();
require 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Vérifier que l'ID du menu est présent
if (!isset($_GET['id'])) {
    die("Aucun menu sélectionné.");
}

$id_menu = (int) $_GET['id'];

// Charger les infos du menu
$stmt = $pdo->prepare("SELECT * FROM Menu WHERE id = ?");
$stmt->execute([$id_menu]);
$menu = $stmt->fetch();

if (!$menu) {
    die("Menu introuvable.");
}

$message = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $adresse = $_POST["adresse"];
    $gsm = $_POST["gsm"];
    $nb_personnes = (int) $_POST["nb_personnes"];
    $id_utilisateur = $_SESSION['user_id'];

    // Calcul du prix total
    $prix_min = $menu['prix_minimum'];
    $nb_min = $menu['nb_personnes_min'];
    $prix_total = ($nb_min > 0) ? ($nb_personnes / $nb_min) * $prix_min : $prix_min;

    // Enregistrer la commande
    $stmt = $pdo->prepare("
        INSERT INTO Commande (id_utilisateur, nom, prenom, email, adresse, gsm, id_menu, nb_personnes, prix_total, statut)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'recu')
    ");

    $ok = $stmt->execute([
        $id_utilisateur, $nom, $prenom, $email, $adresse, $gsm,
        $id_menu, $nb_personnes, $prix_total
    ]);

    if ($ok) {
        header("Location: mes_commandes.php");
        exit;
    } else {
        $message = "❌ Erreur lors de l'enregistrement de la commande.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commander : <?= htmlspecialchars($menu['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-black { background-color: black; color: black; }
        .btn-black:hover { color: white; }
        .btn-danger:hover { color: white; }
        .btn-primary:hover { color: white; }
    </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#menus">Menus</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                <li class="nav-item">
                    <a class="nav-link btn btn-black ms-2" href="mes_commandes.php">Mes commandes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger ms-2" href="deconnexion.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Formulaire de commande -->
<div class="container my-5" style="max-width: 600px;">
    <h2>Commander : <?= htmlspecialchars($menu['titre']) ?></h2>

    <?php if ($message): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Nom :</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Prénom :</label>
            <input type="text" name="prenom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email :</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Adresse :</label>
            <input type="text" name="adresse" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>GSM :</label>
            <input type="text" name="gsm" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nombre de personnes (min <?= $menu['nb_personnes_min'] ?>) :</label>
            <input type="number" name="nb_personnes" min="<?= $menu['nb_personnes_min'] ?>" value="<?= $menu['nb_personnes_min'] ?>" class="form-control" required>
        </div>

        <p><strong>Prix minimum :</strong> <?= number_format($menu['prix_minimum'], 2) ?> €</p>

        <button type="submit" class="btn btn-success">Valider la commande</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
