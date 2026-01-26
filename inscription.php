<?php
require 'config.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $adresse = $_POST['adresse'];
    $gsm = $_POST['gsm'];
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
    <title>Inscription</title>
</head>
<body>
<h1>Créer un compte</h1>

<?php if ($message !== "") echo "<p>$message</p>"; ?>

<form method="POST">
    Nom : <br><br><input type="text" name="nom" required><br><br>
    Prénom : <input type="text" name="prenom" required><br><br>
    Email : <input type="email" name="email" required><br><br>
    Mot de passe : <input type="password" name="motdepasse" required><br><br>
    Adresse : <input type="text" name="adresse" required><br><br>
    GSM : <input type="text" name="gsm" required><br><br>
    <button type="submit">Créer mon compte</button>
</form>
</body>
</html>
