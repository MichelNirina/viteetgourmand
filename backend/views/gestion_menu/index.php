<link rel="stylesheet" href="/viteetgourmand/frontend/assets/css/alert.css">

<h1>Gestion des menus</h1>

<!-- SUCCESS MESSAGE -->
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- ERROR MESSAGE -->
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<a href="?page=gestion_menu&action=create">
    Ajouter un menu
</a>

<hr>

<?php if (!empty($menus)): ?>

    <?php foreach ($menus as $menu): ?>

        <div class="card">

            <h3><?= htmlspecialchars($menu['titre']); ?></h3>

            <p><?= htmlspecialchars($menu['description']); ?></p>

            <p>
                <?= $menu['prix_par_personne']; ?> € / personne
            </p>

            <p>
                <?= $menu['nombre_personne']; ?> personnes minimum
            </p>

            <p>
                Stock : <?= $menu['quantite_restante']; ?>
            </p>

            <a href="?page=gestion_menu&action=edit&id=<?= $menu['menu_id']; ?>">
                ✏ Modifier
            </a>

            <a href="?page=gestion_menu&action=delete&id=<?= $menu['menu_id']; ?>"
               onclick="return confirm('Supprimer ce menu ?')">
                Supprimer
            </a>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p>Aucun menu disponible.</p>

<?php endif; ?>