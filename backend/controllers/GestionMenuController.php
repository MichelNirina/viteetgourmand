<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Menu.php';

class GestionMenuController extends ApiController
{
    public function index()
    {
        $this->requireRole([1, 2]);
        $this->json((new Menu())->getAll());
    }

    public function show()
    {
        $this->requireRole([1, 2]);
        $id   = (int)($_GET['id'] ?? 0);
        $menu = (new Menu())->getById($id);
        if (!$menu) $this->error('Menu introuvable', 404);
        $this->json($menu);
    }

    public function store()
    {
        $this->requireRole([1, 2]);

        $titre             = trim($_POST['titre']             ?? '');
        $description       = trim($_POST['description']       ?? '');
        $nombre_personne   = (int)($_POST['nombre_personne']  ?? 0);
        $prix_par_personne = (float)($_POST['prix_par_personne'] ?? 0);
        $quantite_restante = (int)($_POST['quantite_restante'] ?? 0);
        $regime_id         = (int)($_POST['regime_id']        ?? 0);
        $theme_id          = (int)($_POST['theme_id']         ?? 0);

        if (!$titre || !$nombre_personne || !$prix_par_personne) {
            $this->error('Champs obligatoires manquants', 400);
        }

        (new Menu())->createMenu(
            $titre, $description, $nombre_personne,
            $prix_par_personne, $quantite_restante, $regime_id, $theme_id
        );

        $this->json(['message' => 'Menu créé'], 201);
    }

    public function update()
    {
        $this->requireRole([1, 2]);

        $id                = (int)($_POST['menu_id']          ?? 0);
        $titre             = trim($_POST['titre']             ?? '');
        $description       = trim($_POST['description']       ?? '');
        $nombre_personne   = (int)($_POST['nombre_personne']  ?? 0);
        $prix_par_personne = (float)($_POST['prix_par_personne'] ?? 0);
        $quantite_restante = (int)($_POST['quantite_restante'] ?? 0);
        $regime_id         = (int)($_POST['regime_id']        ?? 0);
        $theme_id          = (int)($_POST['theme_id']         ?? 0);

        if (!$id) $this->error('ID manquant', 400);

        (new Menu())->updateMenu(
            $id, $titre, $description, $nombre_personne,
            $prix_par_personne, $quantite_restante, $regime_id, $theme_id
        );

        $this->json(['message' => 'Menu mis à jour']);
    }

    public function delete()
    {
        $this->requireRole([1, 2]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Menu())->deleteMenu($id);
        $this->json(['message' => 'Menu supprimé']);
    }
}
