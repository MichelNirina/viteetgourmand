<!DOCTYPE html>
<html>
<head>
    <title>Modifier un régime</title>
    <link rel="stylesheet" href="/viteetgourmand/frontend/assets/css/style.css">
</head>
<body>

<h1>Modifier un régime</h1>

<form action="?page=regime&action=update" method="POST">

    <input
        type="hidden"
        name="id"
        value="<?= $regime['regime_id']; ?>"
    >

    <label>Libellé</label><br>

    <input
        type="text"
        name="libelle"
        value="<?= $regime['libelle']; ?>"
        required
    ><br><br>

    <button type="submit">
        Modifier
    </button>

</form>

</body>
</html>