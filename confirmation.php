<?php
require 'config.php';

// Vérifie que l'ID de commande existe
if (!isset($_GET['id'])) {
    die("Aucune commande spécifiée.");
}

$id_commande = (int) $_GET['id'];

// Charger la commande + le menu
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
$commande = $stmt->fetch();

if (!$commande) {
    die("Commande non trouvée.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de commande</title>
    <style>
        body { font-family: Arial; background: #e9ffe9; padding: 20px; }
        .box { background: white; padding: 20px; border-radius: 6px; width: 400px; margin: auto; }
        h2 { color: green; }
    </style>
</head>
<body>

<div class="box">
    <h2>✔ Commande confirmée !</h2>

    <p><strong>Numéro de commande :</strong> <?= $commande['id'] ?></p>

    <p><strong>Nom :</strong> <?= htmlspecialchars($commande['nom']) ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($commande['prenom']) ?></p>

    <p><strong>Menu choisi :</strong> <?= htmlspecialchars($commande['menu_titre']) ?></p>

    <p><strong>Nombre de personnes :</strong> <?= $commande['nb_personnes'] ?></p>

    <p><strong>Prix total :</strong> <?= number_format($commande['prix_total'], 2) ?> €</p>

    <hr>

    <p>📧 Un email de confirmation peut vous être envoyé.</p>
</div>

</body>
</html>
