<h1 class="title">Modifier l'horaire</h1>

<form class="form-card" action="?page=horaire&action=update" method="POST">

    <input type="hidden" name="id" value="<?= $horaire['horaire_id'] ?>">

    <label>Jour</label>
    <input type="text" name="jour" value="<?= htmlspecialchars($horaire['jour']) ?>" required>

    <label>Heure d'ouverture <small>(laisser vide si fermé)</small></label>
    <input type="time" name="heure_ouverture" value="<?= htmlspecialchars($horaire['heure_ouverture'] ?? '') ?>">

    <label>Heure de fermeture <small>(laisser vide si fermé)</small></label>
    <input type="time" name="heure_fermeture" value="<?= htmlspecialchars($horaire['heure_fermeture'] ?? '') ?>">

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="?page=horaire" class="btn btn-secondary">Annuler</a>
    </div>

</form>
