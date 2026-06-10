<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';

require_once __DIR__ . '/../models/Menu.php';
require_once __DIR__ . '/../models/Regime.php';
require_once __DIR__ . '/../models/Theme.php';
require_once __DIR__ . '/../models/Plat.php';

class GestionMenuController extends Controller
{
        public function __construct()
    {
        Session::start();

        $this->requireRole([1, 2]);
    }

    public function index()
    {
        $menuModel = new Menu();

        $menus = $menuModel->getAll();

        ob_start();

        require_once __DIR__ . '/../views/gestion_menu/index.php';

        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function create()
    {
        $regimeModel = new Regime();
        $themeModel = new Theme();

        $regimes = $regimeModel->getAll();
        $themes = $themeModel->getAll();

        ob_start();

        require_once __DIR__ . '/../views/gestion_menu/create.php';

        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function store()
    {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $nombre_personne = $_POST['nombre_personne'];
        $prix_par_personne = $_POST['prix_par_personne'];
        $quantite_restante = $_POST['quantite_restante'];
        $regime_id = $_POST['regime_id'];
        $theme_id = $_POST['theme_id'];

        $menuModel = new Menu();

        $menuModel->createMenu(
            $titre,
            $description,
            $nombre_personne,
            $prix_par_personne,
            $quantite_restante,
            $regime_id,
            $theme_id
        );

        $_SESSION['success'] = "Menu ajouté avec succès.";

        header("Location: ?page=gestion_menu&action=index");
        exit;
    }

    public function edit()
{
    $id = $_GET['id'] ?? null;

    if (!$id) {
        header("Location: ?page=gestion_menu&action=index");
        exit;
    }

    $menuModel = new Menu();
    $menu = $menuModel->getById($id);

    if (!$menu) {
        $_SESSION['error'] = "Menu introuvable.";
        header("Location: ?page=gestion_menu&action=index");
        exit;
    }

    $regimeModel = new Regime();
    $themeModel = new Theme();
    $platModel = new Plat();

    $regimes = $regimeModel->getAll();
    $themes = $themeModel->getAll();

    $plats = $platModel->getByMenuId($id);

    ob_start();
    require_once __DIR__ . '/../views/gestion_menu/edit.php';
    $content = ob_get_clean();

    require_once __DIR__ . '/../views/layout.php';
}

    public function update()
    {
        $id = $_POST['menu_id'];

        $titre = trim($_POST['titre']);
        $description = trim($_POST['description']);
        $nombre_personne = $_POST['nombre_personne'];
        $prix_par_personne = $_POST['prix_par_personne'];
        $quantite_restante = $_POST['quantite_restante'];
        $regime_id = $_POST['regime_id'];
        $theme_id = $_POST['theme_id'];

        $menuModel = new Menu();

        $menuModel->updateMenu(
            $id,
            $titre,
            $description,
            $nombre_personne,
            $prix_par_personne,
            $quantite_restante,
            $regime_id,
            $theme_id
        );

       $_SESSION['success'] = "Menu modifié avec succès.";

        header("Location: ?page=gestion_menu&action=index");
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?page=gestion_menu&action=index");
            exit;
        }

        $menuModel = new Menu();

        $menuModel->deleteMenu($id);

        $_SESSION['success'] = "Menu supprimé avec succès.";

        header("Location: ?page=gestion_menu&action=index");
        exit;
    }
}