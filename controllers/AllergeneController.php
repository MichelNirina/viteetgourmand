<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Allergene.php';
require_once __DIR__ . '/../core/Session.php';

class AllergeneController extends Controller
{
    public function __construct()
    {
        session_start();
        $this->requireRole([1, 2]);
    }
    
    public function index()
    {
        $allergeneModel = new Allergene();

        $allergenes = $allergeneModel->getAll();

        ob_start();
        require_once __DIR__ . '/../views/allergene/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function create()
    {
        ob_start();
        require_once __DIR__ . '/../views/allergene/create.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function store()
    {
        $libelle = trim($_POST['libelle']);

        $allergeneModel = new Allergene();
        $allergeneModel->createAllergene($libelle);

        $_SESSION['success'] = "Allergène ajouté.";

        header("Location: ?page=allergene");
        exit;
    }

    public function delete()
    {
        $id = (int)$_GET['id'];

        $allergeneModel = new Allergene();
        $allergeneModel->deleteAllergene($id);

        $_SESSION['success'] = "Allergène supprimé.";

        header("Location: ?page=allergene");
        exit;
    }
}