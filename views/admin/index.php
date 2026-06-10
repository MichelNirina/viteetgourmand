<link rel="stylesheet" href="assets/css/admin.css">

<h1 class="title">Dashboard Administrateur</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="success-message">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="dashboard">

    <div class="dashboard-card">
        <h2>Gestion employés</h2>
        <p>Créer, modifier et supprimer les comptes employés</p>
        <a class="dashboard-btn" href="?page=admin&action=employees">
            Accéder
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Statistiques</h2>
        <p>Commandes, chiffre d'affaires et performances</p>
        <a class="dashboard-btn" href="?page=admin&action=stats">
            Voir
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Commandes</h2>
        <p>Gestion globale des commandes clients</p>
        <a class="dashboard-btn" href="?page=commande&action=index">
            Gérer
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Avis clients</h2>
        <p>Valider ou refuser les avis clients</p>
        <a class="dashboard-btn" href="?page=avis&action=index">
            Gérer
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Menus</h2>
        <p>Créer, modifier et supprimer les menus</p>
        <a class="dashboard-btn" href="?page=gestion_menu&action=index">
            Accéder
        </a>
    </div>

    <!-- PLAT (AJOUTÉ PROPREMENT) -->
    <div class="dashboard-card">
        <h2>Plats</h2>
        <p>Gérer les plats, photos et allergènes</p>
        <a class="dashboard-btn" href="?page=plat&action=index">
            Accéder
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Allergènes</h2>
        <p>Gérer les allergènes disponibles</p>
        <a class="dashboard-btn" href="?page=allergene&action=index">
            Accéder
        </a>
    </div>

</div>