<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    public function login()
    {
        ob_start();
        require_once __DIR__ . '/../views/auth/login.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }
    public function authenticate()
    {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user;
            $_SESSION['role'] = $user['role_id'];

            if ($user['role_id'] == 1) {
                header("Location: ?page=admin");
            } elseif ($user['role_id'] == 2) {
                header("Location: ?page=employee");
            } else {
                header("Location: ?page=client");
            }

            exit;

        } else {
            echo "Login failed";
        }
    }
}