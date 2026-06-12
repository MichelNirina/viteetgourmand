<?php

require_once __DIR__ . '/../core/Session.php';

class ApiController
{
    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        echo json_encode($data);
        exit;
    }

    protected function error($message, $status = 400)
    {
        $this->json(['error' => $message], $status);
    }

    protected function requireAuth()
    {
        Session::start();
        if (!Session::isLogged()) {
            $this->error('Non authentifié', 401);
        }
    }

    protected function requireRole($roles)
    {
        $this->requireAuth();
        if (!in_array(Session::role(), (array)$roles)) {
            $this->error('Accès refusé', 403);
        }
    }
}
