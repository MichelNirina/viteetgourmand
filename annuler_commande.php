<?php
session_start();
require 'config.php';

// Vérifier que l'id de la commande est présent dans l'URL
if (!isset($_GET['id'])) {
    die("Aucune commande spécifiée.");
}

$id_commande = (int) $_GET['id'];

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour annuler une commande.");
}

$id_utilisateur = $_SESSION['user_id'];

// Vérifier que la commande appartient bien à l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM Commande WHERE id = ? AND id_utilisateur = ?");
$stmt->execute([$id_commande, $id_utilisateur]);
$commande = $stmt->fetch();

if (!$commande) {
    die("Commande introuvable ou non autorisée.");
}

// Supprimer la commande
$stmt = $pdo->prepare("DELETE FROM Commande WHERE id = ? AND id_utilisateur = ?");
$ok = $stmt->execute([$id_commande, $id_utilisateur]);

if ($ok) {
    // Après suppression, afficher message + boutons pour revenir
    echo "<h2>✅ Votre commande a été annulée avec succès !</h2>";
    echo '<a href="index.php" style="padding:10px 20px; background:green; color:white; text-decoration:none; margin-right:10px;">Commander à nouveau</a>';
   } else {
    die("Erreur lors de l'annulation de la commande.");
}
?>
