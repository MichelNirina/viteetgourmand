<!DOCTYPE html>
<html>
<head>
    <title>Ajouter utilisateur</title>
</head>
<body>

<h1>Ajouter un utilisateur</h1>

<form method="POST" action="?page=user&action=store">

    <label>Email :</label>
    <input type="email" name="email" required>

    <br><br>

    <label>Password :</label>
    <input type="password" name="password" required>

    <br><br>

    <label>Prénom :</label>
    <input type="text" name="prenom" required>

    <br><br>

    <label>Téléphone :</label>
    <input type="text" name="telephone">

    <br><br>

    <label>Ville :</label>
    <input type="text" name="ville">

    <br><br>

    <label>Pays :</label>
    <input type="text" name="pays">

    <br><br>

    <label>Adresse :</label>
    <input type="text" name="adresse">

    <br><br>

    <label>Role :</label>
    <select name="role_id">
        <option value="1">admin</option>
        <option value="2">employe</option>
        <option value="3">client</option>
    </select>

    <br><br>

    <button type="submit">Créer</button>

</form>

</body>
</html>