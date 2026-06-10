<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';

require_once __DIR__ . '/../models/Avis.php';
require_once __DIR__ . '/../models/Commande.php';

class AvisController extends Controller
{
    public function create()
    {
        Session::start();

        // uniquement client
        $this->requireRole([3]);

        $numero_commande = $_GET['commande_id'] ?? null;

        if (!$numero_commande) {
            $_SESSION['error'] = "Commande introuvable";
            header("Location: ?page=commande&action=my");
            exit;
        }

        // sécurité : vérifier commande
        $commandeModel = new Commande();
        $commande = $commandeModel->getById($numero_commande);

        if (!$commande) {
            $_SESSION['error'] = "Commande inexistante";
            header("Location: ?page=commande&action=my");
            exit;
        }

        // avis possible uniquement si terminée
        if ($commande['statut'] != 'terminée') {
            $_SESSION['error'] = "Vous ne pouvez donner un avis que pour une commande terminée";
            header("Location: ?page=commande&action=my");
            exit;
        }

        ob_start();
        require_once __DIR__ . '/../views/avis/create.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
        
    }

    public function store()
    {
        Session::start();

        $this->requireRole([3]);

        if (!Session::isLogged()) {
            header("Location: ?page=login");
            exit;
        }

        // sécurité POST
        if (empty($_POST['note']) || empty($_POST['description'])) {
            $_SESSION['error'] = "Tous les champs sont obligatoires";
            header("Location: ?page=commande&action=my");
            exit;
        }

        $note = (int) $_POST['note'];
        $description = trim($_POST['description']);
        $user_id = Session::userId();

        // validation note
        if ($note < 1 || $note > 5) {
            $_SESSION['error'] = "La note doit être entre 1 et 5";
            header("Location: ?page=commande&action=my");
            exit;
        }

        $avisModel = new Avis();

        $avisModel->createAvis(
            $note,
            $description,
            $user_id
        );

        $_SESSION['success'] = "Merci ! Votre avis a été envoyé avec succès.";

        header("Location: ?page=commande&action=my");
        exit;
    }

    public function index()
    {
        Session::start();

        $this->requireRole([1,2]);

        $avisModel = new Avis();
        $avis = $avisModel->getAllAvis();

        ob_start();
        require_once __DIR__ . '/../views/avis/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function validerAvis()
    {
        Session::start();

        $this->requireRole([1,2]);

        $id = $_GET['id'];

        $avisModel = new Avis();
        $avisModel->updateStatut($id, 'valide');

        $_SESSION['success'] = "Avis validé";

        header("Location: ?page=avis");
        exit;
    }

    public function refuserAvis()
    {
        Session::start();

        $this->requireRole([1,2]);

        $id = $_GET['id'];

        $avisModel = new Avis();
        $avisModel->updateStatut($id, 'refuse');

        $_SESSION['success'] = "Avis refusé";

        header("Location: ?page=avis");
        exit;
    }
}