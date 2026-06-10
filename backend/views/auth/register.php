<div class="auth-container">

    <h1>Créer un compte</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error-message">
            <?= $_SESSION['error']; ?>
        </p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="?page=register&action=store" class="auth-form">

        <input type="text" name="nom" placeholder="Nom" required>

        <input type="text" name="prenom" placeholder="Prénom" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="text" name="telephone" placeholder="Téléphone" required>

        <input type="text" name="adresse" placeholder="Adresse" required>

        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit">Créer compte</button>

    </form>

</div>