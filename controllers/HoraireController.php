<?php

require_once __DIR__ . '/../models/Horaire.php';

class HoraireController extends Controller
{
    public function index()
    {
        $this->requireRole([1, 2]);

        $model = new Horaire();
        $horaires = $model->getAll();

        ob_start();
        require_once __DIR__ . '/../views/horaire/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function create()
    {
        $this->requireRole([1, 2]);

        ob_start();
        require_once __DIR__ . '/../views/horaire/create.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function store()
    {
        $this->requireRole([1, 2]);

        $jour = $_POST['jour'];
        $o = $_POST['heure_ouverture'] ?: null;
        $f = $_POST['heure_fermeture'] ?: null;

        $model = new Horaire();
        $model->create($jour, $o, $f);

        $_SESSION['success'] = "Horaire ajouté avec succès.";
        header("Location: ?page=horaire");
        exit;
    }

    public function edit()
    {
        $this->requireRole([1, 2]);

        $id = (int)($_GET['id'] ?? 0);

        $model = new Horaire();
        $horaire = $model->getById($id);

        if (!$horaire) {
            header("Location: ?page=horaire");
            exit;
        }

        ob_start();
        require_once __DIR__ . '/../views/horaire/edit.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function update()
    {
        $this->requireRole([1, 2]);

        $id   = (int)($_POST['id'] ?? 0);
        $jour = $_POST['jour'];
        $o    = $_POST['heure_ouverture'] ?: null;
        $f    = $_POST['heure_fermeture'] ?: null;

        $model = new Horaire();
        $model->update($id, $jour, $o, $f);

        $_SESSION['success'] = "Horaire modifié avec succès.";
        header("Location: ?page=horaire");
        exit;
    }

    public function delete()
    {
        $this->requireRole([1, 2]);

        $id = (int)($_GET['id'] ?? 0);

        $model = new Horaire();
        $model->delete($id);

        $_SESSION['success'] = "Horaire supprimé.";
        header("Location: ?page=horaire");
        exit;
    }
}