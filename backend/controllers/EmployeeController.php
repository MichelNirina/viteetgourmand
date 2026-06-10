<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Commande.php';

class EmployeeController extends ApiController
{
    public function index()
    {
        $this->requireRole(2);
        $this->json(['message' => 'Bienvenue employé', 'user' => Session::user()]);
    }

    public function commandes()
    {
        $this->requireRole([1, 2]);
        $this->json((new Commande())->getAllCommandes());
    }
}
