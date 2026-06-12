<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/User.php';

class UserController extends ApiController
{
    public function index()
    {
        $this->requireRole([1]);
        $this->json((new User())->getAllUsers());
    }

    public function profile()
    {
        $this->requireAuth();
        $user = (new User())->getById(Session::userId());
        unset($user['password']);
        $this->json($user);
    }

    public function store()
    {
        $this->requireRole([1]);
        $email     = trim($_POST['email']    ?? '');
        $password  = trim($_POST['password'] ?? '');
        $prenom    = trim($_POST['prenom']   ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $ville     = trim($_POST['ville']    ?? '');
        $pays      = trim($_POST['pays']     ?? '');
        $adresse   = trim($_POST['adresse']  ?? '');
        $role_id   = (int)($_POST['role_id'] ?? 3);

        if (!$email || !$password || !$prenom) {
            $this->error('Champs obligatoires manquants', 400);
        }

        (new User())->createUser(
            $email, password_hash($password, PASSWORD_DEFAULT),
            $prenom, $telephone, $ville, $pays, $adresse, $role_id
        );

        $this->json(['message' => 'Utilisateur créé'], 201);
    }

    public function update()
    {
        $this->requireAuth();
        $id        = (int)($_POST['user_id']       ?? Session::userId());
        $prenom    = trim($_POST['prenom']          ?? '');
        $email     = trim($_POST['email']           ?? '');
        $telephone = trim($_POST['telephone']       ?? '');
        $ville     = trim($_POST['ville']           ?? '');
        $pays      = trim($_POST['pays']            ?? '');
        $adresse   = trim($_POST['adresse_postale'] ?? '');

        (new User())->updateUser($id, $prenom, $email, $telephone, $ville, $pays, $adresse);
        $this->json(['message' => 'Profil mis à jour']);
    }

    public function delete()
    {
        $this->requireRole([1]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new User())->deleteUser($id);
        $this->json(['message' => 'Utilisateur supprimé']);
    }
}
