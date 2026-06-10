<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Commande.php';

class AdminController extends ApiController
{
    public function index()
    {
        $this->requireRole([1]);
        $model  = new Commande();
        $this->json([
            'total_commandes' => $model->countCommandes(),
            'ca_by_menu'      => $model->getCAByMenu(),
            'stats_by_menu'   => $model->getStatsByMenu(),
        ]);
    }

    public function employees()
    {
        $this->requireRole([1]);
        $this->json((new User())->getEmployees());
    }

    public function storeEmployee()
    {
        $this->requireRole([1]);

        $email     = trim($_POST['email']            ?? '');
        $password  = trim($_POST['password']         ?? '');
        $prenom    = trim($_POST['prenom']            ?? '');
        $telephone = trim($_POST['telephone']         ?? '');
        $ville     = trim($_POST['ville']             ?? '');
        $pays      = trim($_POST['pays']              ?? '');
        $adresse   = trim($_POST['adresse_postale']   ?? '');

        if (!$email || !$password || !$prenom) {
            $this->error('Champs obligatoires manquants', 400);
        }

        (new User())->createUser(
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $prenom, $telephone, $ville, $pays, $adresse,
            2
        );

        $this->json(['message' => 'Employé créé'], 201);
    }

    public function deleteEmployee()
    {
        $this->requireRole([1]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new User())->deleteUser($id);
        $this->json(['message' => 'Employé supprimé']);
    }

    public function stats()
    {
        $this->requireRole([1]);
        $model = new Commande();
        $this->json([
            'total'    => $model->countCommandes(),
            'by_menu'  => $model->getStatsByMenu(),
            'ca'       => $model->getCAByMenu(),
        ]);
    }
}
