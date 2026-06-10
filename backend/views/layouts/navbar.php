<nav class="navbar">

    <a href="?page=home">Accueil</a>

    <a href="?page=menu">Menus</a>

    <a href="?page=contact">Contact</a>

    <?php if (isset($_SESSION['user'])): ?>

        <a href="?page=user&action=index">Mon profil</a>

        <?php if ($_SESSION['user']['role_id'] == 3): ?>
            <a href="?page=commande&action=my">Mes commandes</a>
        <?php endif; ?>

        <?php if ($_SESSION['user']['role_id'] == 2): ?>
            <a href="?page=employee">Dashboard Employé</a>
        <?php endif; ?>

        <?php if ($_SESSION['user']['role_id'] == 1): ?>
            <a href="?page=admin">Dashboard Admin</a>
        <?php endif; ?>

        <a href="?page=logout">Déconnexion</a>

    <?php else: ?>

        <a href="?page=login">Connexion</a>

        <a href="?page=register">Inscription</a>

    <?php endif; ?>

</nav>