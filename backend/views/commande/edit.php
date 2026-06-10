<h1>Modifier ma commande</h1>

<form action="?page=commande&action=update" method="POST">

    <input type="hidden"
           name="numero_commande"
           value="<?= $commande['numero_commande']; ?>">

    <input type="hidden"
           name="menu_id"
           value="<?= $commande['menu_id']; ?>">

    <h3>Menu choisi</h3>

    <p>
        <strong><?= $commande['titre'] ?? 'Menu sélectionné'; ?></strong>
    </p>

    <p>
        Le menu ne peut plus être modifié.
    </p>

    <hr>

    <label>Date de prestation</label><br>

    <input
        type="date"
        name="date_prestation"
        value="<?= $commande['date_prestation']; ?>"
        required>

    <br><br>

    <label>Heure de livraison</label><br>

    <input
        type="time"
        name="heure_livraison"
        value="<?= $commande['heure_livraison']; ?>"
        required>

    <br><br>

    <label>Nombre de personnes</label><br>

    <input
        type="number"
        name="nombre_personne"
        value="<?= $commande['nombre_personne']; ?>"
        min="1"
        required>

    <br><br>

    <button type="submit">
        Enregistrer les modifications
    </button>

</form>