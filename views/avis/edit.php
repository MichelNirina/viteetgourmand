<h1>Modifier avis</h1>

<form method="POST" action="?page=avis&action=update">

    <input type="hidden" name="id" value="<?= $avisItem['avis_id']; ?>">

    <input type="number" name="note" value="<?= $avisItem['note']; ?>">

    <input type="text" name="description" value="<?= $avisItem['description']; ?>">

    <input type="text" name="statut" value="<?= $avisItem['statut']; ?>">

    <select name="user_id">
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['user_id']; ?>"
                <?= $u['user_id'] == $avisItem['user_id'] ? 'selected' : '' ?>>
                <?= $u['prenom']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button>Modifier</button>

</form>