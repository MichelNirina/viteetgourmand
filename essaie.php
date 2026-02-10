<?php
// confirmation.php

// Vérifie si le formulaire de commande a été soumis
if (isset($_POST['submit'])) {
    // Récupération des données de la commande
    $nom_client = htmlspecialchars($_POST['nom_client']);
    $menu_choisi = htmlspecialchars($_POST['menu_choisi']);
    $quantite = intval($_POST['quantite']);
} else {
    // Redirection si accès direct à la page
    header("Location: menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            padding: 50px;
        }
        .confirmation-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .confirmation-box h1 {
            color: #4CAF50;
        }
        .confirmation-box p {
            font-size: 18px;
        }
        .btn-home {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-home:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="confirmation-box">
        <h1>Commande Confirmée ! ✅</h1>
        <p>Merci, <strong><?php echo $nom_client; ?></strong>, pour votre commande.</p>
        <p>Vous avez commandé : <strong><?php echo $quantite; ?> x <?php echo $menu_choisi; ?></strong></p>
        <p>Votre commande sera préparée sous peu.</p>
        <a class="btn-home" href="menu.php">Retour au menu</a>
    </div>
</body>
</html>
