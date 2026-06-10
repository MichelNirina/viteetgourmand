<h1>Modifier allergène</h1>

<form method="POST" action="?page=allergene&action=update">

    <input type="hidden" name="id" value="<?= $allergene['allergene_id']; ?>">

    <input type="text" name="libelle" value="<?= $allergene['libelle']; ?>">

    <button>Modifier</button>
</form>