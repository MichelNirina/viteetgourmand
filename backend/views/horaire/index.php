<h1 class="title">Gestion des horaires</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="actions-bar">
    <a class="btn btn-primary" href="?page=horaire&action=create">+ Ajouter un horaire</a>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Jour</th>
                <th>Ouverture</th>
                <th>Fermeture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horaires as $horaire): ?>
            <tr>
                <td><?= htmlspecialchars($horaire['jour']) ?></td>
                <td><?= $horaire['heure_ouverture'] ? htmlspecialchars($horaire['heure_ouverture']) : '<em>Fermé</em>' ?></td>
                <td><?= $horaire['heure_fermeture'] ? htmlspecialchars($horaire['heure_fermeture']) : '<em>Fermé</em>' ?></td>
                <td>
                    <a href="?page=horaire&action=edit&id=<?= $horaire['horaire_id'] ?>">Modifier</a>
                    |
                    <a href="?page=horaire&action=delete&id=<?= $horaire['horaire_id'] ?>"
                       onclick="return confirm('Supprimer cet horaire ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
