<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Menu.php';
require_once __DIR__ . '/../models/Plat.php';

class MenuController extends ApiController
{
    public function index()
    {
        $model = new Menu();

        $menus = $model->filterMenus(
            $_GET['prix_min'] ?? null,
            $_GET['prix_max'] ?? null,
            $_GET['theme']    ?? null,
            $_GET['regime']   ?? null,
            $_GET['personnes'] ?? null
        );

        $this->json($menus);
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->error('ID manquant', 400);
        }

        $menuModel = new Menu();
        $platModel = new Plat();

        $menu = $menuModel->getById($id);

        if (!$menu) {
            $this->error('Menu introuvable', 404);
        }

        $plats = $platModel->getByMenuId($id);

        foreach ($plats as &$plat) {
            $plat['allergenes'] = $platModel->getAllergenesByPlat($plat['plat_id']);
        }

        $menu['plats'] = $plats;

        $this->json($menu);
    }
}
