<link rel="stylesheet" href="/viteetgourmand/frontend/assets/css/plat.css">

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<h1 class="title">Gestion des plats</h1>

<a class="btn-add" href="?page=plat&action=create">
    + Ajouter un plat
</a>

<div class="plat-container">

    <?php if (!empty($plats)): ?>

        <?php foreach ($plats as $plat): ?>

            <div class="plat-card">

                <!-- IMAGE -->
                <?php if (!empty($plat['photo'])): ?>
                    <img src="<?= htmlspecialchars($plat['photo']); ?>" alt="plat">
                <?php else: ?>
                    <div class="no-image">Aucune image</div>
                <?php endif; ?>

                <!-- TITRE -->
                <h3><?= htmlspecialchars($plat['titre_plat']); ?></h3>

                <!-- ALLERGÈNES -->
                <p>
                    <strong>Allergènes :</strong>
                    <?= !empty($plat['allergenes']) 
                        ? htmlspecialchars($plat['allergenes']) 
                        : 'Aucun'; ?>
                </p>

                <!-- ACTIONS -->
                <div class="actions">

                    <a href="?page=plat&action=edit&id=<?= (int)$plat['plat_id']; ?>">
                        Modifier
                    </a>

                    <a href="?page=plat&action=delete&id=<?= (int)$plat['plat_id']; ?>"
                       onclick="return confirm('Supprimer ce plat ?')">
                        Supprimer
                    </a>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Aucun plat disponible.</p>

    <?php endif; ?>

</div>