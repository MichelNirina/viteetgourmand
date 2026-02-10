<?php
session_start();
include "config.php";

$user_id = $_SESSION['user_id'] ?? null;

// Récupérer prénom et nom de l'utilisateur connecté
$prenom = null;
$nom = null;
if ($user_id) {
    $stmt = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $prenom = ucfirst(substr($user['prenom'],0,1)) . ".";
        $nom = $user['nom'];
    }
}

// Récupérer tous les avis
$stmt = $pdo->query("
    SELECT u.prenom, u.nom, a.note, a.commentaire, a.date_creation
    FROM avis a
    JOIN utilisateur u ON u.id = a.id_utilisateur
    ORDER BY a.date_creation DESC
");
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Vite & Gourmand</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Header */
.header-link { transition: color 0.3s; color: black; }
.header-link:hover { color: #007bff; }
.btn-hover-red:hover { color: white !important; background-color: #dc3545 !important; }
.user-name { font-size: 0.75rem; line-height: 1rem; display: block; }
.navbar-nav .nav-item { margin-left: 10px; margin-right: 10px; }

/* Général */
h2, h5 { font-weight: 600; text-align: left; }
p { line-height: 1.5; text-align: left; }
section { margin-bottom: 60px; }
.section-title { color: #ff6f3c; margin-bottom: 25px; font-weight: 600; }

/* Équipe */
.team-member { background-color: #ffffff; padding: 15px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 20px; display: flex; align-items: center; min-height: 150px; }
.team-member img { object-fit: cover; width: 100px; height: 100px; border-radius: 12px; margin-right: 15px; }
.team-text { flex-grow: 1; }

/* Avis clients */
.review-container { padding: 0; }
.review { background-color: #ffffff; border-radius: 10px; padding: 15px 20px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.review-header { display:flex; align-items:center; justify-content:space-between; font-weight:600; margin-bottom: 5px; }
.review-header .stars { font-size:0.9rem; color:#ffcc00; margin-left:10px; }
.comment { margin-top:5px; padding:5px 10px; background-color:#f0f0f0; border-left:3px solid #007bff; border-radius:6px; }

/* Footer */
footer p { font-size: 0.9rem; margin: 0; text-align: center; }

/* Responsive */
@media(max-width:767px){
    .team-member { flex-direction: column; align-items: flex-start; min-height:auto; }
    .team-member img { margin-right: 0; margin-bottom: 10px; }
}
</style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item mx-2"><a class="nav-link header-link" href="index.php">Accueil</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="mes_menus.php">Mes Menus</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="mes_commandes.php">Mes Commandes</a></li>
                <li class="nav-item mx-2"><a class="nav-link header-link" href="contact.php">Contact</a></li>

                <?php if($user_id): ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-danger btn-hover-red px-3" href="deconnexion.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mx-2"><a class="nav-link btn btn-success btn-hover-white px-3" href="connexion.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Bienvenue -->
<section class="container my-5">
    <?php if($prenom && $nom): ?>
        <h1>Bonjour <?= htmlspecialchars($prenom).' '.htmlspecialchars($nom) ?> </h1>
    <?php endif; ?>
    <h2 class="section-title">Bienvenue chez Vite & Gourmand </h2>
    <p>Passionnés par la cuisine depuis plus de 25 ans à Bordeaux, nous créons des menus variés et raffinés pour tous vos événements, du repas de famille aux grandes occasions.</p>
    <p>Notre priorité est de vous offrir <strong>des plats de qualité</strong> et un <strong>service professionnel</strong>, pour que chaque repas soit un moment de plaisir et de partage.</p>
</section>

<!-- Équipe -->
<section class="container">
    <h2 class="section-title">Notre équipe</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="team-member">
                <img src="uploads/julie.jpg" alt="Julie">
                <div class="team-text">
                    <h5>Julie</h5>
                    <p>Spécialiste en organisation et cuisine créative, Julie assure la qualité et l’innovation des menus.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="team-member">
                <img src="uploads/jose.jpg" alt="José">
                <div class="team-text">
                    <h5>José</h5>
                    <p>Expert en gastronomie et service, José garantit une expérience culinaire mémorable pour chaque événement.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avis clients -->
<section class="container" id="avis">
    <h2 class="section-title">Avis de nos clients</h2>
    <div class="review-container">
        <?php if(count($avis) > 0): ?>
            <?php foreach($avis as $a): ?>
            <div class="review">
                <div class="review-header">
                    <span><?= htmlspecialchars($a['prenom'].' '.$a['nom']) ?></span>
                    <span class="stars"><?= str_repeat('⭐', $a['note']) ?></span>
                </div>
                <p><?= htmlspecialchars($a['commentaire']) ?></p>
                <div class="comment">Publié le <?= date('d/m/Y', strtotime($a['date_creation'])) ?></div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Pas d'avis pour le moment.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer class="bg-light p-4">
    <p>Horaires : Lundi-Dimanche 9h-19h | Mentions légales | CGV</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
