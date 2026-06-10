<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Allergene.php';

class AllergeneController extends ApiController
{
    public function index()
    {
        $this->requireRole([1, 2]);
        $this->json((new Allergene())->getAll());
    }

    public function store()
    {
        $this->requireRole([1, 2]);
        $libelle = trim($_POST['libelle'] ?? '');
        if (!$libelle) $this->error('Libellé obligatoire', 400);
        (new Allergene())->createAllergene($libelle);
        $this->json(['message' => 'Allergène ajouté'], 201);
    }

    public function delete()
    {
        $this->requireRole([1, 2]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Allergene())->deleteAllergene($id);
        $this->json(['message' => 'Allergène supprimé']);
    }
}
