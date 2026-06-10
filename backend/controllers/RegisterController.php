<?php

require_once __DIR__ . '/../models/User.php';

class RegisterController extends Controller
{
    public function index()
    {
        ob_start();
        require_once __DIR__ . '/../views/auth/register.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function store()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];
        $ville = $_POST['ville'] ?? null;
        $pays = $_POST['pays'] ?? null;
        $adresse = $_POST['adresse'];

        if (strlen($password) < 10) {
            $_SESSION['error'] = "Mot de passe trop court (10 caractères minimum)";
            header("Location: ?page=register");
            exit;
        }

        $model = new User();

        if ($model->findByEmail($email)) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            header("Location: ?page=register");
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $model->createUser(
            $email,
            $hash,
            $prenom,
            $telephone,
            $ville,
            $pays,
            $adresse,
            3
        );

        $_SESSION['success'] = "Compte créé avec succès ! Vous pouvez vous connecter.";

        header("Location: ?page=login");
        exit;
    }
}