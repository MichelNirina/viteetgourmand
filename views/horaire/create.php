<h1 class="title">Ajouter un horaire</h1>

<form class="form-card" action="?page=horaire&action=store" method="POST">

    <label>Jour</label>
    <input type="text" name="jour" placeholder="ex: Lundi" required>

    <label>Heure d'ouverture <small>(laisser vide si fermé)</small></label>
    <input type="time" name="heure_ouverture">

    <label>Heure de fermeture <small>(laisser vide si fermé)</small></label>
    <input type="time" name="heure_fermeture">

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="?page=horaire" class="btn btn-secondary">Annuler</a>
    </div>

</form>
