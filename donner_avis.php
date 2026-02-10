<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'donner_avis.php?id=' . ($_GET['id'] ?? '');
    header("Location: connexion.php");
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

// Vérifier que l'ID de commande est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Commande introuvable.");
}

$id_commande = (int)$_GET['id'];

// Vérifier si la commande appartient à l'utilisateur
$stmt = $pdo->prepare("SELECT c.id, m.titre AS menu_titre FROM commande c JOIN menus m ON c.id_menu = m.id WHERE c.id = ? AND c.id_utilisateur = ?");
$stmt->execute([$id_commande, $id_utilisateur]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("Commande introuvable ou accès refusé.");
}

$message = "";

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = (int)$_POST['note'];
    $commentaire = trim($_POST['commentaire']);

    if ($note < 1 || $note > 5) {
        $message = "Veuillez sélectionner une note valide.";
    } else {
        // Vérifier si un avis existe déjà pour cette commande et utilisateur
        $stmtCheck = $pdo->prepare("SELECT id FROM avis WHERE id_commande = ? AND id_utilisateur = ?");
        $stmtCheck->execute([$id_commande, $id_utilisateur]);
        $existant = $stmtCheck->fetch();

        if ($existant) {
            $stmtUpdate = $pdo->prepare("UPDATE avis SET note = ?, commentaire = ?, date_creation = NOW() WHERE id = ?");
            $stmtUpdate->execute([$note, $commentaire, $existant['id']]);
            $message = "Votre avis a été mis à jour.";
        } else {
            $stmtInsert = $pdo->prepare("INSERT INTO avis (id_commande, id_utilisateur, note, commentaire) VALUES (?, ?, ?, ?)");
            $stmtInsert->execute([$id_commande, $id_utilisateur, $note, $commentaire]);
            $message = "Merci pour votre avis !";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Donner un avis – Vite & Gourmand</title>
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

<!-- FORMULAIRE D'AVIS -->
<div class="container d-flex justify-content-center my-5">
    <div class="card p-4 shadow" style="width:100%; max-width:500px;">
        <h2 class="mb-4 text-center">Donner un avis</h2>

        <?php if($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <p><strong>Commande :</strong> <?= htmlspecialchars($commande['menu_titre']) ?></p>

        <form method="POST">
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <select name="note" id="note" class="form-select" required>
                    <option value="">-- Sélectionnez --</option>
                    <?php for($i=1;$i<=5;$i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> <?= $i===1?'étoile':'étoiles' ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="commentaire" class="form-label">Commentaire</label>
                <textarea name="commentaire" id="commentaire" class="form-control" rows="4"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-light p-4 mt-5">
    <p style="text-align:center; margin:0;">Horaires : Lundi-Dimanche 9h-19h | Mentions légales | CGV</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
