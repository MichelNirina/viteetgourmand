<h1>Modifier thème</h1>

<form method="POST" action="?page=theme&action=update">

    <input type="hidden" name="id" value="<?= $theme['theme_id']; ?>">

    <input type="text" name="libelle" value="<?= $theme['libelle']; ?>">

    <button>Modifier</button>
</form>