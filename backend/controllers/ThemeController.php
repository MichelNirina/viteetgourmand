<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Theme.php';

class ThemeController extends ApiController
{
    public function index()
    {
        $this->requireRole([1]);
        $this->json((new Theme())->getAll());
    }

    public function store()
    {
        $this->requireRole([1]);
        $libelle = trim($_POST['libelle'] ?? '');
        if (!$libelle) $this->error('Libellé obligatoire', 400);
        (new Theme())->createTheme($libelle);
        $this->json(['message' => 'Thème ajouté'], 201);
    }

    public function update()
    {
        $this->requireRole([1]);
        $id      = (int)($_POST['id']      ?? 0);
        $libelle = trim($_POST['libelle']  ?? '');
        if (!$id) $this->error('ID manquant', 400);
        (new Theme())->updateTheme($id, $libelle);
        $this->json(['message' => 'Thème mis à jour']);
    }

    public function delete()
    {
        $this->requireRole([1]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Theme())->deleteTheme($id);
        $this->json(['message' => 'Thème supprimé']);
    }
}
