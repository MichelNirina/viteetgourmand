<!DOCTYPE html>
<html>
<head>
    <title>Régimes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h1>Liste des régimes</h1>

<p>
    <a href="?page=regime&action=create">
        Ajouter un régime
    </a>
</p>

<?php foreach ($regimes as $regime): ?>

    <div class="menu-card">

        <h3><?= $regime['libelle']; ?></h3>

        <a href="?page=regime&action=edit&id=<?= $regime['regime_id']; ?>">
            Modifier
        </a>

        |

        <a href="?page=regime&action=delete&id=<?= $regime['regime_id']; ?>"
           onclick="return confirm('Supprimer ce régime ?')">
            Supprimer
        </a>

    </div>

<?php endforeach; ?>

</body>
</html>