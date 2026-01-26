<?php
$host = "localhost";
$db = "vite_gourmand";
$user = "root"; // par défaut XAMPP
$pass = "";     // par défaut XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie !"; // on peut commenter pour éviter d'afficher
} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
