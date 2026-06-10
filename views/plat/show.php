<link rel="stylesheet" href="assets/css/plat.css">

<div class="plat-show">

    <?php if (!empty($plat['photo'])): ?>
        <img class="plat-image"
             src="<?= htmlspecialchars($plat['photo']); ?>"
             alt="plat">
    <?php endif; ?>

    <h1><?= htmlspecialchars($plat['titre_plat']); ?></h1>

    <p>
        <strong>Type :</strong>
        <?= htmlspecialchars($plat['type']); ?>
    </p>

    <p>
        <strong>Menu :</strong>
        <?= htmlspecialchars($plat['menu_titre'] ?? 'Non défini'); ?>
    </p>

    <p>
        <strong>Allergènes :</strong><br>

        <?php if (!empty($allergenes)): ?>
            <?php foreach ($allergenes as $a): ?>
                <span class="tag">
                    <?= htmlspecialchars($a['libelle']); ?>
                </span>
            <?php endforeach; ?>
        <?php else: ?>
            Aucun allergène
        <?php endif; ?>

    </p>

    <a class="btn-back" href="?page=plat">
        Retour
    </a>

</div>