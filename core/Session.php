<?php

class Session
{
    // Démarrer la session proprement (UNE SEULE FOIS)
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Récupérer l'utilisateur connecté
    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    // ID utilisateur connecté
    public static function userId()
    {
        return $_SESSION['user']['user_id'] ?? null;
    }

    // Rôle utilisateur
    public static function role()
    {
        return $_SESSION['role'] ?? null;
    }

    // Vérifier si connecté
    public static function isLogged()
    {
        return isset($_SESSION['user']);
    }

    // Logout propre
    public static function logout()
    {
        self::start();
        session_unset();
        session_destroy();
    }
}