<link rel="stylesheet" href="assets/css/home.css">

<h1 class="contact-title">Contact</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="contact-success">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<form class="contact-form" action="?page=contact&action=send" method="POST">

    <label>Titre</label>
    <input type="text" name="titre" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Description</label>
    <textarea name="description" rows="6" required></textarea>

    <button type="submit">
        Envoyer
    </button>

</form>