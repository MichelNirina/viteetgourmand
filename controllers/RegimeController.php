<?php

require_once __DIR__ . '/../models/Regime.php';

class RegimeController extends Controller
{
    // LISTE DES RÉGIMES
    public function index()
    {
        $this->requireRole(1);

        $regimeModel = new Regime();
        $regimes = $regimeModel->getAllRegimes();

        require_once __DIR__ . '/../views/regime/index.php';
    }

    // FORMULAIRE AJOUT
    public function create()
    {
        $this->requireRole(1);

        require_once __DIR__ . '/../views/regime/create.php';
    }

    // ENREGISTRER RÉGIME
    public function store()
    {
        $this->requireRole(1);

        $libelle = $_POST['libelle'];

        $regimeModel = new Regime();
        $regimeModel->createRegime($libelle);

        header("Location: ?page=regime");
        exit;
    }

    // FORMULAIRE MODIFICATION
    public function edit()
    {
        $this->requireRole(1);

        $id = $_GET['id'];

        $regimeModel = new Regime();
        $regime = $regimeModel->getById($id);

        require_once __DIR__ . '/../views/regime/edit.php';
    }

    // MODIFIER RÉGIME
    public function update()
    {
        $this->requireRole(1);

        $id = $_POST['id'];
        $libelle = $_POST['libelle'];

        $regimeModel = new Regime();
        $regimeModel->updateRegime($id, $libelle);

        header("Location: ?page=regime");
        exit;
    }

    // SUPPRIMER RÉGIME
    public function delete()
    {
        $this->requireRole(1);

        $id = $_GET['id'];

        $regimeModel = new Regime();
        $regimeModel->deleteRegime($id);

        header("Location: ?page=regime");
        exit;
    }
}