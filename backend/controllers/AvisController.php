<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Avis.php';

class AvisController extends ApiController
{
    public function index()
    {
        $this->requireRole([1, 2]);
        $this->json((new Avis())->getAllAvis());
    }

    public function store()
    {
        $this->requireRole([3]);

        $note        = (int)($_POST['note']        ?? 0);
        $description = trim($_POST['description']  ?? '');

        if (!$note || !$description) {
            $this->error('Note et description obligatoires', 400);
        }
        if ($note < 1 || $note > 5) {
            $this->error('La note doit être entre 1 et 5', 400);
        }

        (new Avis())->createAvis($note, $description, Session::userId());
        $this->json(['message' => 'Avis envoyé'], 201);
    }

    public function validerAvis()
    {
        $this->requireRole([1, 2]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Avis())->updateStatut($id, 'valide');
        $this->json(['message' => 'Avis validé']);
    }

    public function refuserAvis()
    {
        $this->requireRole([1, 2]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Avis())->updateStatut($id, 'refuse');
        $this->json(['message' => 'Avis refusé']);
    }

    public function delete()
    {
        $this->requireRole([1]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Avis())->deleteAvis($id);
        $this->json(['message' => 'Avis supprimé']);
    }
}
