<link rel="stylesheet" href="/viteetgourmand/frontend/assets/css/plat.css">

<h1>Ajouter un plat</h1>

<form method="POST"
      action="?page=plat&action=store"
      enctype="multipart/form-data">

    <label>Titre du plat</label>
    <input type="text" name="titre" required>

    <label>Type</label>
    <select name="type" required>
        <option value="entree">Entrée</option>
        <option value="plat">Plat</option>
        <option value="dessert">Dessert</option>
    </select>

    <label>Photo</label>
    <input type="file" name="photo" required>

    <label>Menu associé</label>

    <select name="menu_id" required>

        <option disabled selected value="">
            -- Choisir un menu --
        </option>

        <?php foreach ($menus as $menu): ?>
            <option value="<?= (int)$menu['menu_id']; ?>">
                <?= htmlspecialchars($menu['titre']); ?>
            </option>
        <?php endforeach; ?>

    </select>

    <label>Allergènes</label>

    <div class="allergenes-container">

        <?php foreach ($allergenes as $allergene): ?>

            <label class="checkbox-label">

                <input
                    type="checkbox"
                    name="allergenes[]"
                    value="<?= $allergene['allergene_id']; ?>"
                >

                <?= htmlspecialchars($allergene['libelle']); ?>

            </label>

        <?php endforeach; ?>

    </div>

    <button type="submit">
        Créer
    </button>

</form>