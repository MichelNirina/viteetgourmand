<?php
// Récupère l'URL de la base depuis la variable d'environnement Fly.io
$database_url = getenv('DATABASE_URL');

if (!$database_url) {
    die("Erreur : DATABASE_URL non définie !");
}

// Parse l'URL PostgreSQL
$parts = parse_url($database_url);

$host = $parts['host'];
$port = $parts['port'];
$dbname = ltrim($parts['path'], '/');
$user = $parts['user'];
$pass = $parts['pass'];

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", 
        $user, 
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}
