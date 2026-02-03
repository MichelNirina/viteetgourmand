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
   2. Vérifier ID commande
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Aucune commande spécifiée.");
}

$id_commande   = (int) $_GET['id'];
$id_utilisateur = $_SESSION['user_id'];

/* =========================
   3. Vérifier propriété + statut
========================= */
$stmt = $pdo->prepare("
    SELECT id, statut
    FROM Commande
    WHERE id = ? AND id_utilisateur = ?
");
$stmt->execute([$id_commande, $id_utilisateur]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("❌ Commande introuvable ou accès refusé.");
}

/* =========================
   4. Bloquer si déjà acceptée
========================= */
if ($commande['statut'] === 'accepte') {
    die("❌ Cette commande a déjà été acceptée et ne peut plus être annulée.");
}

/* =========================
   5. Suppression de la commande
========================= */
$stmt = $pdo->prepare("
    DELETE FROM Commande
    WHERE id = ? AND id_utilisateur = ?
");
$ok = $stmt->execute([$id_commande, $id_utilisateur]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Annulation de commande</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4 text-center w-100" style="max-width: 500px;">

        <?php if ($ok): ?>
            <h2 class="text-success mb-3">✅ Commande annulée avec succès</h2>
            <p class="mb-4">
                Votre commande a bien été annulée.<br>
                Vous pouvez en passer une nouvelle ou consulter vos commandes.
            </p>

            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="index.php" class="btn btn-success">
                    🍽 Commander à nouveau
                </a>
                <a href="mes_commandes.php" class="btn btn-primary">
                    📋 Mes commandes
                </a>
            </div>

        <?php else: ?>
            <h2 class="text-danger mb-3">❌ Erreur</h2>
            <p>Une erreur est survenue lors de l'annulation.</p>
            <a href="mes_commandes.php" class="btn btn-primary">
                Retour à mes commandes
            </a>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
