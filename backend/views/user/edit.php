<link rel="stylesheet" href="/viteetgourmand/frontend/assets/css/style.css">

<h1 class="title">Mon profil</h1>

<?php if (!empty($success)): ?>
    <div class="success-message">
        <?= $success; ?>
    </div>
<?php endif; ?>

<form method="POST" action="?page=user&action=update" class="form-profil">

    <label>Prénom</label>
    <input type="text" name="prenom" value="<?= $user['prenom']; ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= $user['email']; ?>" required>

    <label>Téléphone</label>
    <input type="text" name="telephone" value="<?= $user['telephone']; ?>" required>

    <label>Ville</label>
    <input type="text" name="ville" value="<?= $user['ville']; ?>">

    <label>Pays</label>
    <input type="text" name="pays" value="<?= $user['pays']; ?>">

    <label>Adresse postale</label>
    <input type="text" name="adresse_postale" value="<?= $user['adresse_postale']; ?>">

    <button type="submit">Modifier</button>

</form>