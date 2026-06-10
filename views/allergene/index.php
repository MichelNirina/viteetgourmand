<h1>Gestion des allergènes</h1>

<a href="?page=allergene&action=create">
    Ajouter un allergène
</a>

<hr>

<?php foreach ($allergenes as $allergene): ?>

    <div class="card">

        <h3>
            <?= htmlspecialchars($allergene['libelle']); ?>
        </h3>

        <a href="?page=allergene&action=delete&id=<?= $allergene['allergene_id']; ?>"
           onclick="return confirm('Supprimer ?')">
            Supprimer
        </a>

    </div>

<?php endforeach; ?>