<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Role.php';

class RoleController extends ApiController
{
    public function index()
    {
        $this->requireRole([1]);
        $this->json((new Role())->getAllRoles());
    }

    public function store()
    {
        $this->requireRole([1]);
        $libelle = trim($_POST['libelle'] ?? '');
        if (!$libelle) $this->error('Libellé obligatoire', 400);
        (new Role())->createRole($libelle);
        $this->json(['message' => 'Rôle ajouté'], 201);
    }

    public function delete()
    {
        $this->requireRole([1]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Role())->deleteRole($id);
        $this->json(['message' => 'Rôle supprimé']);
    }
}
