<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un régime</title>
    <link rel="stylesheet" href="/viteetgourmand/frontend/assets/css/style.css">
</head>
<body>

<h1>Ajouter un régime</h1>

<form action="?page=regime&action=store" method="POST">

    <label>Libellé</label><br>
    <input type="text" name="libelle" required><br><br>

    <button type="submit">
        Enregistrer
    </button>

</form>

</body>
</html>