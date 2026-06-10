<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Session.php';

class UserController extends Controller
{
   
    public function index()
    {
        Session::start();

        if (!Session::isLogged()) {
            header("Location: ?page=login");
            exit;
        }

        $userModel = new User();

        $user = $userModel->getById(Session::userId());

        ob_start();
        require_once __DIR__ . '/../views/user/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    // FORM CREATE
    public function create()
    {
        $this->requireRole(1);

        require_once __DIR__ . '/../views/user/create.php';
    }

    // INSERT USER
    public function store()
    {
        $this->requireRole(1);

        $email = $_POST['email'];
        $password = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT);
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];
        $ville = $_POST['ville'];
        $pays = $_POST['pays'];
        $adresse = $_POST['adresse'];
        $role_id = $_POST['role_id'];

        $userModel = new User();
        $userModel->createUser(
            $email,
            $password,
            $prenom,
            $telephone,
            $ville,
            $pays,
            $adresse,
            $role_id
        );

        header("Location: ?page=user");
        exit;
    }

    // FORM EDIT
    public function edit()
    {
        Session::start();

        if (!Session::isLogged()) {
            header("Location: ?page=login");
            exit;
        }

        $user = Session::user();

        ob_start();
        require_once __DIR__ . '/../views/user/edit.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    // UPDATE USER
    public function update()
    {
        Session::start();

        if (!Session::isLogged()) {
            header("Location: ?page=login");
            exit;
        }

        $user_id = Session::userId();

        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $ville = $_POST['ville'];
        $pays = $_POST['pays'];
        $adresse_postale = $_POST['adresse_postale'];

        $userModel = new User();

        $userModel->updateUser(
            $user_id,
            $prenom,
            $email,
            $telephone,
            $ville,
            $pays,
            $adresse_postale
        );

        // Recharger les données depuis la base
        $user = $userModel->getById($user_id);

        // Mettre à jour toute la session
        $_SESSION['user'] = $user;

        $_SESSION['success'] = "Profil mis à jour avec succès";

        header("Location: ?page=user&action=index");
        exit;
    }

    // DELETE USER
    public function delete()
    {
        $this->requireRole(1);

        $id = $_GET['id'];

        $userModel = new User();
        $userModel->deleteUser($id);

        header("Location: ?page=user");
        exit;
    }
}