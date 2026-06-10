<link rel="stylesheet" href="assets/css/admin_menu.css">

<h1>Créer un menu</h1>

<form class="admin-form" method="POST" action="?page=gestion_menu&action=store">

    <label>Titre</label>
    <input type="text" name="titre" required>

    <label>Description</label>
    <input type="text" name="description" required>

    <label>Nombre de personnes</label>
    <input type="number" name="nombre_personne" required>

    <label>Prix par personne</label>
    <input type="number" step="0.01" name="prix_par_personne" required>

    <label>Quantité restante</label>
    <input type="number" name="quantite_restante" required>

    <!-- REGIME -->
    <label>Régime</label>
    <select name="regime_id" required>
        <?php foreach ($regimes as $regime): ?>
            <option value="<?= $regime['regime_id']; ?>">
                <?= htmlspecialchars($regime['libelle']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- THEME -->
    <label>Thème</label>
    <select name="theme_id" required>
        <?php foreach ($themes as $theme): ?>
            <option value="<?= $theme['theme_id']; ?>">
                <?= htmlspecialchars($theme['libelle']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Créer le menu</button>

</form>