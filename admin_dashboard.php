<?php
session_start();
include "config.php";

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Vous devez être administrateur pour accéder à cette page.");
}

// Récupérer le nom et prénom de l'admin
$stmt = $pdo->prepare("SELECT nom, prenom FROM Utilisateur WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            transition: 0.3s;
        }
        .card-link {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="admin_dashboard.php">Vite & Gourmand - Admin</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu -->
<div class="container my-5">
    <h2>Bienvenue, <?= htmlspecialchars($admin['nom'] . ' ' . $admin['prenom']) ?> !</h2>
    <p>Voici vos options de gestion :</p>

    <div class="row mt-4">
        <!-- Gestion des menus -->
        <div class="col-md-4 mb-3">
            <a href="admin_menu.php" class="card-link">
                <div class="card card-hover text-center p-4">
                    <h5 class="card-title">Gestion des Menus</h5>
                    <p class="card-text">Ajouter, modifier ou supprimer des menus.</p>
                </div>
            </a>
        </div>

        <!-- Gestion des commandes -->
        <div class="col-md-4 mb-3">
            <a href="employe_commandes.php" class="card-link">
                <div class="card card-hover text-center p-4">
                    <h5 class="card-title">Gestion des Commandes</h5>
                    <p class="card-text">Voir et mettre à jour le statut des commandes.</p>
                </div>
            </a>
        </div>

        <!-- Gestion des utilisateurs -->
        <div class="col-md-4 mb-3">
            <a href="admin_utilisateurs.php" class="card-link">
                <div class="card card-hover text-center p-4">
                    <h5 class="card-title">Gestion des Utilisateurs</h5>
                    <p class="card-text">Voir, modifier ou supprimer des comptes utilisateurs.</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
