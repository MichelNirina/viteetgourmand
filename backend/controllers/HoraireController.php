<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Horaire.php';

class HoraireController extends ApiController
{
    public function index()
    {
        $this->requireRole([1, 2]);
        $this->json((new Horaire())->getAll());
    }

    public function store()
    {
        $this->requireRole([1, 2]);
        $jour = trim($_POST['jour'] ?? '');
        $o    = $_POST['heure_ouverture'] ?: null;
        $f    = $_POST['heure_fermeture'] ?: null;
        if (!$jour) $this->error('Jour obligatoire', 400);
        (new Horaire())->create($jour, $o, $f);
        $this->json(['message' => 'Horaire ajouté'], 201);
    }

    public function update()
    {
        $this->requireRole([1, 2]);
        $id   = (int)($_POST['id'] ?? 0);
        $jour = trim($_POST['jour'] ?? '');
        $o    = $_POST['heure_ouverture'] ?: null;
        $f    = $_POST['heure_fermeture'] ?: null;
        if (!$id) $this->error('ID manquant', 400);
        (new Horaire())->update($id, $jour, $o, $f);
        $this->json(['message' => 'Horaire modifié']);
    }

    public function delete()
    {
        $this->requireRole([1, 2]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Horaire())->delete($id);
        $this->json(['message' => 'Horaire supprimé']);
    }
}
