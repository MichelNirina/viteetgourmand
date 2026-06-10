<link rel="stylesheet" href="/viteetgourmand/frontend/assets/css/menu-show.css">

<div class="menu-container">

    <h1 class="menu-title"><?= htmlspecialchars($menu['titre']); ?></h1>

    <p class="menu-description">
        <?= htmlspecialchars($menu['description']); ?>
    </p>

    <div class="menu-info">
        <p><strong>Thème :</strong> <?= htmlspecialchars($menu['theme_libelle'] ?? 'Non défini'); ?></p>
        <p><strong>Régime :</strong> <?= htmlspecialchars($menu['regime_libelle'] ?? 'Non défini'); ?></p>
        <p><strong>Nombre minimum :</strong> <?= (int)$menu['nombre_personne']; ?> personnes</p>
        <p><strong>Prix :</strong> <?= number_format($menu['prix_par_personne'], 2); ?> € / personne</p>
        <p><strong>Stock :</strong> <?= (int)$menu['quantite_restante']; ?></p>
    </div>

    <hr>

    <h2>🍽️ Plats inclus dans le menu</h2>

    <?php if (!empty($plats)): ?>

        <div class="plat-grid">

            <?php foreach ($plats as $plat): ?>

                <div class="plat-card">

                    <?php if (!empty($plat['photo'])): ?>
                        <img src="<?= htmlspecialchars($plat['photo']); ?>" alt="<?= htmlspecialchars($plat['titre_plat']); ?>">
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($plat['titre_plat']); ?></h3>

                    <p class="plat-type">
                        <?= htmlspecialchars($plat['type'] ?? 'Plat'); ?>
                    </p>

                    <p><strong>Allergènes :</strong></p>

                    <?php if (!empty($plat['allergenes'])): ?>
                        <div class="allergenes">
                            <?php foreach ($plat['allergenes'] as $a): ?>
                                <span class="tag">
                                    <?= htmlspecialchars($a['libelle']); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Aucun allergène</p>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <p>Aucun plat associé à ce menu.</p>

    <?php endif; ?>

    <hr>

    <div class="menu-footer">

        <?php if (!empty($_SESSION['user'])): ?>

            <?php if ($menu['quantite_restante'] > 0): ?>
                <a class="btn-order"
                   href="?page=commande&action=create&menu_id=<?= $menu['menu_id']; ?>">
                    Commander ce menu
                </a>
            <?php else: ?>
                <p class="out-of-stock">Menu en rupture de stock</p>
            <?php endif; ?>

        <?php else: ?>
            <a class="btn-login" href="?page=login">
                Se connecter pour commander
            </a>
        <?php endif; ?>

    </div>

</div>