<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends ApiController
{
    public function me()
    {
        Session::start();
        if (!Session::isLogged()) {
            $this->json(null);
        }
        $user = Session::user();
        unset($user['password']);
        $this->json($user);
    }

    public function authenticate()
    {
        Session::start();
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email || !$password) {
            $this->error('Email et mot de passe requis', 400);
        }

        $userModel = new User();
        $user      = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['role'] = $user['role_id'];
            unset($user['password']);
            $this->json(['user' => $user, 'role' => (int)$user['role_id']]);
        } else {
            $this->error('Email ou mot de passe incorrect', 401);
        }
    }

    public function logout()
    {
        Session::logout();
        $this->json(['message' => 'Déconnecté']);
    }
}
