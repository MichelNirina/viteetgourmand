<?php

require_once __DIR__ . '/../models/Theme.php';

class ThemeController extends Controller
{
    public function index()
    {
        $this->requireRole(1);

        $model = new Theme();
        $themes = $model->getAllThemes();

        require_once __DIR__ . '/../views/theme/index.php';
    }

    public function create()
    {
        $this->requireRole(1);
        require_once __DIR__ . '/../views/theme/create.php';
    }

    public function store()
    {
        $this->requireRole(1);

        $libelle = $_POST['libelle'];

        $model = new Theme();
        $model->createTheme($libelle);

        header("Location: ?page=theme");
        exit;
    }

    public function edit()
    {
        $this->requireRole(1);

        $id = $_GET['id'];

        $model = new Theme();
        $theme = $model->getById($id);

        require_once __DIR__ . '/../views/theme/edit.php';
    }

    public function update()
    {
        $this->requireRole(1);

        $id = $_POST['id'];
        $libelle = $_POST['libelle'];

        $model = new Theme();
        $model->updateTheme($id, $libelle);

        header("Location: ?page=theme");
        exit;
    }

    public function delete()
    {
        $this->requireRole(1);

        $id = $_GET['id'];

        $model = new Theme();
        $model->deleteTheme($id);

        header("Location: ?page=theme");
        exit;
    }
}