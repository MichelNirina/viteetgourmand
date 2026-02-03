<?php
require 'config.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $adresse = trim($_POST['adresse']);
    $gsm = trim($_POST['gsm']);
    $role = 'utilisateur';

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM Utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $message = "❌ Cet email est déjà utilisé.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, adresse, gsm, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nom, $prenom, $email, $motdepasse, $adresse, $gsm, $role])) {
            $message = "✅ Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
        } else {
            $message = "❌ Erreur lors de la création du compte.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Header simple -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Vite & Gourmand</a>
    </div>
</nav>

<!-- Formulaire centré et responsive -->
<div class="container d-flex justify-content-center align-items-center my-5">
    <div class="card p-4 shadow" style="width: 100%; max-width: 500px;">
        <h2 class="mb-4 text-center">Créer un compte</h2>

        <?php if ($message !== ""): ?>
            <div class="alert <?= strpos($message,'✅') !== false ? 'alert-success' : 'alert-danger' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" id="nom" required>
            </div>
            <div class="col-md-6">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" name="prenom" id="prenom" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="col-md-6">
                <label for="motdepasse" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="motdepasse" id="motdepasse" required>
            </div>
            <div class="col-12">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" class="form-control" name="adresse" id="adresse" required>
            </div>
            <div class="col-12">
                <label for="gsm" class="form-label">GSM</label>
                <input type="text" class="form-control" name="gsm" id="gsm" required>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary w-100 mt-3">Créer mon compte</button>
            </div>
        </form>

        <div class="mt-3 text-center">
            <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
