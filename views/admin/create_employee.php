<h1 class="title">Créer un employé</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="success-message">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="error-message">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="?page=admin&action=storeEmployee" class="form-profil">

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mot de passe</label>
    <input type="password" name="password" required>

    <label>Prénom</label>
    <input type="text" name="prenom">

    <label>Téléphone</label>
    <input type="text" name="telephone">

    <label>Ville</label>
    <input type="text" name="ville">

    <label>Pays</label>
    <input type="text" name="pays">

    <label>Adresse</label>
    <input type="text" name="adresse_postale">

    <button type="submit">Créer employé</button>

</form>