<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Vous devez être administrateur.");
}

// Récupérer le nom et prénom de l'admin
$stmt = $pdo->prepare("SELECT nom, prenom FROM Utilisateur WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer tous les messages de contact avec info utilisateur
$stmt = $pdo->query("
    SELECT c.*, u.nom AS nom_user, u.prenom AS prenom_user
    FROM contact c
    LEFT JOIN utilisateur u ON c.id_utilisateur = u.id
    ORDER BY c.date_envoi DESC
");
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Messages Contact – Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
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

<!-- Header Admin -->
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
    <h2>Messages Contact</h2>
   
    <?php if(empty($contacts)): ?>
        <div class="alert alert-info">Aucun message reçu.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Titre</th>
                        <th>Message</th>
                        <th>Date envoi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($contacts as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td>
                                <?= $c['nom_user'] ? htmlspecialchars($c['prenom_user'].' '.$c['nom_user']) : "Invité" ?>
                            </td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['titre']) ?></td>
                            <td><?= nl2br(htmlspecialchars($c['message'])) ?></td>
                            <td><?= (new DateTime($c['date_envoi']))->format('d.m.Y H:i') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
