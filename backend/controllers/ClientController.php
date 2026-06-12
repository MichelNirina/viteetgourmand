<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Commande.php';

class ClientController extends ApiController
{
    public function index()
    {
        $this->requireRole(3);
        $commandes = (new Commande())->getCommandesByUser(Session::userId());
        $this->json($commandes);
    }
}
