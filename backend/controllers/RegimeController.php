<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Regime.php';

class RegimeController extends ApiController
{
    public function index()
    {
        $this->requireRole([1, 2]);
        $this->json((new Regime())->getAll());
    }

    public function store()
    {
        $this->requireRole([1]);
        $libelle = trim($_POST['libelle'] ?? '');
        if (!$libelle) $this->error('Libellé obligatoire', 400);
        (new Regime())->createRegime($libelle);
        $this->json(['message' => 'Régime ajouté'], 201);
    }

    public function update()
    {
        $this->requireRole([1]);
        $id      = (int)($_POST['id']      ?? 0);
        $libelle = trim($_POST['libelle']  ?? '');
        if (!$id) $this->error('ID manquant', 400);
        (new Regime())->updateRegime($id, $libelle);
        $this->json(['message' => 'Régime mis à jour']);
    }

    public function delete()
    {
        $this->requireRole([1]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Regime())->deleteRegime($id);
        $this->json(['message' => 'Régime supprimé']);
    }
}
