<div class="auth-container">

    <h1>Connexion</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <p class="success-message">
            <?= $_SESSION['success']; ?>
        </p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error-message">
            <?= $_SESSION['error']; ?>
        </p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="?page=login&action=auth" class="auth-form">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

</div>