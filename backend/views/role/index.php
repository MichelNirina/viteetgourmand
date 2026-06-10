<h1>Liste des rôles</h1>

<a href="?page=role&action=create">Ajouter un rôle</a>

<?php foreach ($roles as $role): ?>

    <p>
        <?= $role['libelle']; ?>

        <a href="?page=role&action=edit&id=<?= $role['role_id']; ?>">
            Modifier
        </a>

        <a href="?page=role&action=delete&id=<?= $role['role_id']; ?>">
            Supprimer
        </a>
    </p>

<?php endforeach; ?>