<link rel="stylesheet" href="assets/css/plat.css">

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<h1>Modifier un plat</h1>

<form method="POST"
      action="?page=plat&action=update"
      enctype="multipart/form-data">

    <input type="hidden" name="plat_id" value="<?= $plat['plat_id']; ?>">

    <label>Titre du plat</label>
    <input type="text"
           name="titre"
           value="<?= htmlspecialchars($plat['titre_plat']); ?>"
           required>

    <label>Photo actuelle</label><br>

    <?php if (!empty($plat['photo'])): ?>
        <img src="<?= htmlspecialchars($plat['photo']); ?>" width="120">
    <?php endif; ?>

    <br><br>

    <label>Nouvelle photo</label>
    <input type="file" name="photo">

    <label>Menu associé</label>

    <select name="menu_id" required>
        <option value="">-- Choisir un menu --</option>

        <?php foreach ($menus as $menu): ?>
            <option value="<?= (int)$menu['menu_id']; ?>"
                <?= ((int)$menu['menu_id'] === (int)$plat['menu_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($menu['titre']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Allergènes</label>

    <div class="allergenes-container">

        <?php foreach ($allergenes as $allergene): ?>

            <label class="checkbox-label">

                <input type="checkbox"
                       name="allergenes[]"
                       value="<?= (int)$allergene['allergene_id']; ?>"
                       <?= in_array($allergene['allergene_id'], $platAllergenes ?? []) ? 'checked' : '' ?>>

                <?= htmlspecialchars($allergene['libelle']); ?>

            </label>

        <?php endforeach; ?>

    </div>

    <button type="submit">
        Modifier
    </button>

</form>