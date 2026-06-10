<h1>Nos Menus</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="success-message">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<p>
    Découvrez nos menus disponibles.
</p>

<!-- FILTRES CLIENT -->
<form method="GET">

    <input type="hidden" name="page" value="menu">

    <input type="number" name="prix_min" placeholder="Prix minimum">

    <input type="number" name="prix_max" placeholder="Prix maximum">

    <input type="number" name="personnes" placeholder="Nombre minimum personnes">

    <select name="theme">
        <option value="">Tous les thèmes</option>
        <option value="Noel">Noel</option>
        <option value="Pâques">Pâques</option>
        <option value="Classique">Classique</option>
    </select>

    <select name="regime">
        <option value="">Tous les régimes</option>
        <option value="Vegan">Vegan</option>
        <option value="Végétarien">Végétarien</option>
        <option value="Classique">Classique</option>
    </select>

    <button type="submit">Filtrer</button>

</form>

<hr>

<?php if (!empty($menus)): ?>

    <?php foreach ($menus as $menu): ?>

        <div class="card">

            <?php if (!empty($menu['photo_preview'])): ?>
                <img src="<?= htmlspecialchars($menu['photo_preview']); ?>" alt="<?= htmlspecialchars($menu['titre']); ?>">
            <?php endif; ?>

            <h3><?= htmlspecialchars($menu['titre']); ?></h3>

            <p><?= htmlspecialchars($menu['description']); ?></p>

            <p>💰 Prix : <?= $menu['prix_par_personne']; ?> €</p>

            <p>👥 Min : <?= $menu['nombre_personne']; ?> personnes</p>

            <p>📦 Stock : <?= $menu['quantite_restante']; ?></p>

            <!-- CLIENT ACTION -->
            <a class="btn"
               href="?page=menu&action=show&id=<?= $menu['menu_id']; ?>">
                Voir détails
            </a>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p>Aucun menu disponible.</p>

<?php endif; ?>