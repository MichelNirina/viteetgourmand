<link rel="stylesheet" href="/viteetgourmand/frontend/assets/css/admin_menu.css">

<h1>Modifier le menu</h1>

<form class="admin-form"
      method="POST"
      action="?page=gestion_menu&action=update">

    <input type="hidden" name="menu_id" value="<?= $menu['menu_id']; ?>">

    <!-- TITRE -->
    <label>Titre</label>
    <input type="text"
           name="titre"
           value="<?= htmlspecialchars($menu['titre']); ?>"
           required>

    <!-- DESCRIPTION -->
    <label>Description</label>
    <input type="text"
           name="description"
           value="<?= htmlspecialchars($menu['description']); ?>"
           required>

    <!-- PERSONNES -->
    <label>Nombre de personnes</label>
    <input type="number"
           name="nombre_personne"
           value="<?= $menu['nombre_personne']; ?>"
           required>

    <!-- PRIX -->
    <label>Prix par personne</label>
    <input type="number"
           step="0.01"
           name="prix_par_personne"
           value="<?= $menu['prix_par_personne']; ?>"
           required>

    <!-- STOCK -->
    <label>Quantité restante</label>
    <input type="number"
           name="quantite_restante"
           value="<?= $menu['quantite_restante']; ?>"
           required>

    <!-- REGIME -->
    <label>Régime</label>
    <select name="regime_id" required>
        <?php foreach ($regimes as $regime): ?>
            <option value="<?= $regime['regime_id']; ?>"
                <?= $regime['regime_id'] == $menu['regime_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($regime['libelle']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- THEME -->
    <label>Thème</label>
    <select name="theme_id" required>
        <?php foreach ($themes as $theme): ?>
            <option value="<?= $theme['theme_id']; ?>"
                <?= $theme['theme_id'] == $menu['theme_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($theme['libelle']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <hr>

    <!-- PLATS -->
    <h3>Plats du menu</h3>

    <?php if (!empty($plats)): ?>
        <ul>
            <?php foreach ($plats as $plat): ?>
                <li>
                    <?= htmlspecialchars($plat['titre_plat']); ?>

                    <?php if (!empty($plat['allergenes'])): ?>
                        <small>
                            (Allergènes :
                            <?= htmlspecialchars($plat['allergenes']); ?>
                            )
                        </small>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun plat associé à ce menu.</p>
    <?php endif; ?>

    <button type="submit">Modifier le menu</button>

</form>