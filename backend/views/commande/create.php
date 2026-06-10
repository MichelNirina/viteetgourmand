<h1>Commande du menu</h1>

<form method="POST" action="?page=commande&action=store">

    <h3>Client</h3>

    <input type="text" value="<?= $_SESSION['user']['prenom']; ?>" readonly>
    <input type="email" value="<?= $_SESSION['user']['email']; ?>" readonly>
    <input type="text" value="<?= $_SESSION['user']['telephone']; ?>" readonly>

    <hr>

    <h3>Prestation</h3>

    <input type="date" name="date_prestation" required>
    <input type="time" name="heure_livraison" required>
    <input type="text" name="adresse" placeholder="Adresse livraison" required>

    <input type="number"
       id="nb_personne"
       name="nombre_personne"
       min="<?= $menu['nombre_personne']; ?>"
       value="<?= $menu['nombre_personne']; ?>"
       placeholder="Nombre de personnes"
       required>

    <hr>

    <h3>Menu sélectionné</h3>

    <input type="hidden" id="prix_unitaire" value="<?= $menu['prix_par_personne']; ?>">
    <input type="hidden" name="menu_id" value="<?= $menu['menu_id']; ?>">

    <p><strong><?= $menu['titre']; ?></strong></p>

    <p><?= $menu['prix_par_personne']; ?> € / personne</p>

    <p>Minimum : <?= $menu['nombre_personne']; ?> personnes</p>

    <hr>

    <!-- PRIX TOTAL -->
    <h3>
        Prix total : <span id="prix_total"><?= $menu['prix_par_personne'] * $menu['nombre_personne']; ?></span> €
    </h3>
   
    <hr>

    <button type="submit">Valider commande</button>

</form>