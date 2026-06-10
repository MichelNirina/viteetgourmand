<?php

require_once __DIR__ . "/../models/Menu.php";
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Plat.php';

class MenuController extends Controller
{
    public function index()
    {
        $prix_min = $_GET['prix_min'] ?? null;
        $prix_max = $_GET['prix_max'] ?? null;
        $theme = $_GET['theme'] ?? null;
        $regime = $_GET['regime'] ?? null;
        $personnes = $_GET['personnes'] ?? null;

        $model = new Menu();

        $menus = $model->filterMenus(
            $prix_min,
            $prix_max,
            $theme,
            $regime,
            $personnes
        );

        ob_start();

        require_once __DIR__ . '/../views/menu/index.php';

        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?page=menu");
            exit;
        }

        $menuModel = new Menu();
        $platModel = new Plat();

        $menu = $menuModel->getById($id);

        if (!$menu) {
            header("Location: ?page=menu");
            exit;
        }

        // plats du menu
        $plats = $platModel->getByMenuId($id);

        // allergènes par plat
        foreach ($plats as &$plat) {
            $plat['allergenes'] = $platModel->getAllergenesByPlat($plat['plat_id']);
        }

        ob_start();
        require_once __DIR__ . '/../views/menu/show.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

}