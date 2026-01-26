<?php
include "config.php";

// Vérifier que l'ID du menu est passé en URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Menu non spécifié !");
}

$id_menu = intval($_GET['id']); // sécurité : convertir en entier

// Récupérer les informations du menu
$stmt = $pdo->prepare("SELECT * FROM Menu WHERE id = ?");
$stmt->execute([$id_menu]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu introuvable !");
}

// Récupérer les plats associés
$stmt2 = $pdo->prepare("
    SELECT p.nom, p.type, p.description 
    FROM Plat p
    JOIN Menu_Plat mp ON p.id = mp.id_plat
    WHERE mp.id_menu = ?
");
$stmt2->execute([$id_menu]);
$plats = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du menu - <?php echo htmlspecialchars($menu['titre']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1><?php echo htmlspecialchars($menu['titre']); ?></h1>
    <p><?php echo htmlspecialchars($menu['description']); ?></p>

    <p>
        Thème : <strong><?php echo $menu['theme']; ?></strong> |
        Régime : <strong><?php echo $menu['regime']; ?></strong>
    </p>

    <p>
        Prix : <strong><?php echo number_format($menu['prix_minimum'], 2); ?> €</strong>
        pour <strong><?php echo $menu['nb_personnes_min']; ?></strong> personnes
    </p>

    <p>Stock disponible : <?php echo $menu['stock']; ?></p>
    <p>Conditions : <?php echo nl2br($menu['conditions']); ?></p>

    <h3>Plats du menu :</h3>
    <ul>
        <?php foreach ($plats as $plat): ?>
            <li>
                <strong><?php echo ucfirst($plat['type']); ?> :</strong>
                <?php echo $plat['nom']; ?> - <?php echo $plat['description']; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="commande.php?id=<?php echo $menu['id']; ?>" class="btn btn-success">
        Commander ce menu
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
