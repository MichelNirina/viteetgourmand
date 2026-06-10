<div class="avis-container">

    <h1 class="avis-title">Ajouter un avis</h1>

    <form method="POST" action="?page=avis&action=store" class="avis-form">

        <label>Note (1 à 5)</label>
        <input type="number" name="note" min="1" max="5" required>

        <label>Description</label>
        <input type="text" name="description" required>

        <!-- statut géré automatiquement -->
        <input type="hidden" name="statut" value="visible">

        <button type="submit">Ajouter</button>

    </form>

</div>