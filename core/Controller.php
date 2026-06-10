<?php

class Controller
{
    protected function requireLogin()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login");
            exit;
        }
    }

    protected function requireRole($roles)
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login");
            exit;
        }

        $role = $_SESSION['user']['role_id'];

        if (!in_array($role, (array)$roles)) {
            echo "Accès refusé";
            exit;
        }
    }
}