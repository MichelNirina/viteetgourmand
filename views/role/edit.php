<form action="?page=role&action=update" method="POST">

    <input type="hidden"
           name="id"
           value="<?= $role['role_id']; ?>">

    <label>Libellé</label>

    <input type="text"
           name="libelle"
           value="<?= $role['libelle']; ?>">

    <button type="submit">Modifier</button>

</form>