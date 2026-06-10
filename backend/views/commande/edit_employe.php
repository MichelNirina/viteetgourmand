<h1 class="title">Modifier le statut</h1>

<form method="POST"
      action="?page=commande&action=updateEmploye">

    <input type="hidden"
           name="numero_commande"
           value="<?= $commande['numero_commande']; ?>">

    <p>
        <strong>Commande :</strong>
        <?= $commande['numero_commande']; ?>
    </p>

    <p>
        <strong>Statut actuel :</strong>
        <?= $commande['statut']; ?>
    </p>

    <label>Statut</label>

    <select name="statut">

        <option value="en attente"
            <?= $commande['statut']=='en attente' ? 'selected' : '' ?>>
            En attente
        </option>

        <option value="acceptée"
            <?= $commande['statut']=='acceptée' ? 'selected' : '' ?>>
            Acceptée
        </option>

        <option value="en préparation"
            <?= $commande['statut']=='en préparation' ? 'selected' : '' ?>>
            En préparation
        </option>

        <option value="en cours de livraison"
            <?= $commande['statut']=='en cours de livraison' ? 'selected' : '' ?>>
            En cours de livraison
        </option>

        <option value="livré"
            <?= $commande['statut']=='livré' ? 'selected' : '' ?>>
            Livré
        </option>

        <option value="en attente du retour de matériel"
            <?= $commande['statut']=='en attente du retour de matériel' ? 'selected' : '' ?>>
            Retour matériel
        </option>

        <option value="terminée"
            <?= $commande['statut']=='terminée' ? 'selected' : '' ?>>
            Terminée
        </option>

    </select>

    <br><br>

    <button type="submit">
        Enregistrer
    </button>

</form>