<h1 class="title">Gestion des employés</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="success-message">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<a class="btn-add" href="?page=admin&action=createEmployee">
    + Ajouter un employé
</a>

<table class="commande-table">

    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Prénom</th>
            <th>Téléphone</th>
            <th>Ville</th>
            <th>Pays</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        <?php if (!empty($employees)): ?>

            <?php foreach ($employees as $e): ?>

                <tr>
                    <td><?= $e['user_id']; ?></td>
                    <td><?= $e['email']; ?></td>
                    <td><?= $e['prenom']; ?></td>
                    <td><?= $e['telephone']; ?></td>
                    <td><?= $e['ville']; ?></td>
                    <td><?= $e['pays']; ?></td>

                    <td>
                        <a class="btn-delete"
                           href="?page=admin&action=deleteEmployee&id=<?= $e['user_id']; ?>"
                           onclick="return confirm('Supprimer cet employé ?')">
                            Supprimer
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">Aucun employé trouvé</td>
            </tr>

        <?php endif; ?>

    </tbody>

</table>