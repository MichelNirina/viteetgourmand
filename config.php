<?php
// Configuration MySQL pour XAMPP
$host = '127.0.0.1';
$dbname = 'vite_gourmand';
$user = 'root';
$pass = ''; // mot de passe vide par défaut sur XAMPP

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
        $user, 
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}
