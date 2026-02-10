<?php
session_start();

// Détruire toutes les données de session
$_SESSION = [];
session_unset();
session_destroy();

// Rediriger vers la page d'accueil après déconnexion
header("Location: index.php");
exit;
