<?php
session_start();
require 'config.php';

/* =========================
   1. Vérification de l'ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Aucune commande spécifiée.");
}

$id_commande = (int) $_GET['id'];

/* =========================
   2. Récupération de la commande
========================= */
$stmt = $pdo->prepare("
    SELECT 
        Commande.id,
        Commande.nom,
        Commande.prenom,
        Commande.email,
        Commande.adresse,
        Commande.gsm,
        Commande.nb_personnes,
        Commande.prix_total,
        Menu.titre AS menu_titre,
        Menu.prix_minimum
    FROM Commande
    INNER JOIN Menu ON Commande.id_menu = Menu.id
    WHERE Commande.id = ?
");
$stmt->execute([$id_commande]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("❌ Commande non trouvée.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de commande</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            border-radius: 15px;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4 w-100" style="max-width: 550px;">

        <h2 class="text-success text-center mb-4">
            ✅ Commande confirmée !
        </h2>

        <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item">
                <strong>Numéro :</strong> <?= $commande['id'] ?>
            </li>
            <li class="list-group-item">
                <strong>Nom :</strong> <?= htmlspecialchars($commande['nom']) ?>
            </li>
            <li class="list-group-item">
                <strong>Prénom :</strong> <?= htmlspecialchars($commande['prenom']) ?>
            </li>
            <li class="list-group-item">
                <strong>Email :</strong> <?= htmlspecialchars($commande['email']) ?>
            </li>
            <li class="list-group-item">
                <strong>Téléphone :</strong> <?= htmlspecialchars($commande['gsm']) ?>
            </li>
            <li class="list-group-item">
                <strong>Menu :</strong> <?= htmlspecialchars($commande['menu_titre']) ?>
            </li>
            <li class="list-group-item">
                <strong>Nombre de personnes :</strong> <?= $commande['nb_personnes'] ?>
            </li>
            <li class="list-group-item">
                <strong>Prix total :</strong>
                <span class="text-success fw-bold">
                    <?= number_format($commande['prix_total'], 2, ',', ' ') ?> €
                </span>
            </li>
        </ul>

        <p class="text-center text-muted mb-4">
            📧 Un email de confirmation peut vous être envoyé.
        </p>

        <div class="d-grid gap-2">
            <a href="index.php" class="btn btn-primary">
                🏠 Retour à l'accueil
            </a>

            <a href="annuler_commande.php?id=<?= $commande['id'] ?>"
               class="btn btn-outline-danger"
               onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                ❌ Annuler la commande
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
