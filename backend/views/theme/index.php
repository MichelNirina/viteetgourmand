<h1>Thèmes</h1>

<a href="?page=theme&action=create">Ajouter</a>

<?php foreach ($themes as $theme): ?>

    <p>
        <?= $theme['libelle']; ?>

        <a href="?page=theme&action=edit&id=<?= $theme['theme_id']; ?>">Modifier</a>
        <a href="?page=theme&action=delete&id=<?= $theme['theme_id']; ?>">Supprimer</a>
    </p>

<?php endforeach; ?>