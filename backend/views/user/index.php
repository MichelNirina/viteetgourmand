<?php if (!empty($_SESSION['success'])): ?>
    <div class="success-message">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<h1 class="title">Mon profil</h1>

<div class="profile-card">

    <p>
        <strong>Prénom :</strong>
        <?= $user['prenom']; ?>
    </p>

    <p>
        <strong>Email :</strong>
        <?= $user['email']; ?>
    </p>

    <p>
        <strong>Téléphone :</strong>
        <?= $user['telephone']; ?>
    </p>

    <p>
        <strong>Ville :</strong>
        <?= $user['ville']; ?>
    </p>

    <p>
        <strong>Pays :</strong>
        <?= $user['pays']; ?>
    </p>

    <p>
        <strong>Adresse :</strong>
        <?= $user['adresse_postale']; ?>
    </p>

    <a class="btn-profile" href="?page=user&action=edit">
        Modifier mon profil
    </a>

</div>