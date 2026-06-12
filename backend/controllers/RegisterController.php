<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/User.php';

class RegisterController extends ApiController
{
    public function store()
    {
        $email     = trim($_POST['email']     ?? '');
        $password  = trim($_POST['password']  ?? '');
        $prenom    = trim($_POST['prenom']    ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse   = trim($_POST['adresse']   ?? '');
        $ville     = trim($_POST['ville']     ?? '');
        $pays      = trim($_POST['pays']      ?? '');

        if (!$email || !$password || !$prenom) {
            $this->error('Email, mot de passe et prénom sont obligatoires', 400);
        }

        if (strlen($password) < 10) {
            $this->error('Mot de passe trop court (10 caractères minimum)', 400);
        }

        $model = new User();

        if ($model->findByEmail($email)) {
            $this->error('Cet email est déjà utilisé', 409);
        }

        $model->createUser(
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $prenom, $telephone, $ville, $pays, $adresse,
            3
        );

        $this->json(['message' => 'Compte créé avec succès'], 201);
    }
}
