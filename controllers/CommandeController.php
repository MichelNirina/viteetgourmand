<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';

require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Menu.php';

class CommandeController extends Controller
{
    public function index()
    {
        $this->requireRole([1, 2]);

        $commandeModel = new Commande();
        $commandes = $commandeModel->getAllCommandes();

        ob_start();
        require_once __DIR__ . '/../views/commande/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function create()
    {
        $this->requireRole([1, 2, 3]);

        $menu_id = $_GET['menu_id'] ?? null;

        if (!$menu_id) {
            $_SESSION['error'] = "Menu introuvable";
            header("Location: ?page=menu");
            exit;
        }

        $menuModel = new Menu();
        $menu = $menuModel->getById($menu_id);

        ob_start();
        require_once __DIR__ . '/../views/commande/create.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

   public function store()
    {
        Session::start();

        $this->requireRole([1, 2, 3]);

        if (!Session::isLogged()) {
            header("Location: ?page=login");
            exit;
        }

        // sécurité POST
        if (empty($_POST['menu_id']) || empty($_POST['nombre_personne'])) {
            $_SESSION['error'] = "Données de commande manquantes";
            header("Location: ?page=menu");
            exit;
        }

        $menu_id = (int) $_POST['menu_id'];
        $nombre_personne = (int) $_POST['nombre_personne'];

        $date_prestation = $_POST['date_prestation'] ?? null;
        $heure_livraison = $_POST['heure_livraison'] ?? null;

        $user_id = Session::userId();
        $date_commande = date('Y-m-d');

        // menu
        $menuModel = new Menu();
        $menu = $menuModel->getById($menu_id);

        if (!$menu) {
            $_SESSION['error'] = "Menu introuvable";
            header("Location: ?page=menu");
            exit;
        }

        // validation minimum personnes
        if ($nombre_personne < $menu['nombre_personne']) {
            $_SESSION['error'] = "Minimum " . $menu['nombre_personne'] . " personnes";
            header("Location: ?page=commande&action=create&menu_id=" . $menu_id);
            exit;
        }

        // prix
        $prix_menu = $menu['prix_par_personne'] * $nombre_personne;

        if ($nombre_personne >= $menu['nombre_personne'] + 5) {
            $prix_menu *= 0.9;
        }

        $prix_livraison = 5;

        $numero_commande = uniqid("CMD");
        $statut = "en attente";

        //si DB attend INT -> mets 0/1 (PAS "non")
        $pret_materiel = 0;
        $restitution_materiel = 0;

        // insert
        $commandeModel = new Commande();

        $commandeModel->createCommande(
            $numero_commande,
            $date_commande,
            $date_prestation,
            $heure_livraison,
            $prix_menu,
            $nombre_personne,
            $prix_livraison,
            $statut,
            $pret_materiel,
            $restitution_materiel,
            $user_id,
            $menu_id
        );

        $_SESSION['success'] = "Commande enregistrée avec succès !";

        header("Location: ?page=commande&action=my");
        exit;
    }
    public function edit()
    {
        Session::start();

        $this->requireRole([3]);

        $numero_commande = $_GET['id'];

        $commandeModel = new Commande();
        $commande = $commandeModel->getById($numero_commande);

        // Interdire la modification si la commande est acceptée
        if ($commande['statut'] == 'acceptée') {

            $_SESSION['error'] = "Cette commande ne peut plus être modifiée.";

            header("Location: ?page=commande&action=my");
            exit;
        }

        ob_start();
        require_once __DIR__ . '/../views/commande/edit.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
        
    }

    public function update()
    {
        Session::start();

        $this->requireRole([3]);

        if (!Session::isLogged()) {
            header("Location: ?page=login");
            exit;
        }

        $numero_commande = $_POST['numero_commande'];

        $commandeModel = new Commande();
        $commande = $commandeModel->getById($numero_commande);

        // Sécurité
        if ($commande['statut'] == 'acceptée') {

            $_SESSION['error'] = "Cette commande ne peut plus être modifiée.";

            header("Location: ?page=commande&action=my");
            exit;
        }

        // Champs modifiables
        $date_prestation = $_POST['date_prestation'];
        $heure_livraison = $_POST['heure_livraison'];
        $nombre_personne = $_POST['nombre_personne'];

        // Champs conservés
        $date_commande = $commande['date_commande'];
        $prix_menu = $commande['prix_menu'];
        $prix_livraison = $commande['prix_livraison'];
        $statut = $commande['statut'];
        $pret_materiel = $commande['pret_materiel'];
        $restitution_materiel = $commande['restitution_materiel'];
        $menu_id = $commande['menu_id'];

        $user_id = Session::userId();

        $commandeModel->updateCommande(
            $numero_commande,
            $date_commande,
            $date_prestation,
            $heure_livraison,
            $prix_menu,
            $nombre_personne,
            $prix_livraison,
            $statut,
            $pret_materiel,
            $restitution_materiel,
            $user_id,
            $menu_id
        );

        $_SESSION['success'] = "Commande modifiée avec succès.";

        header("Location: ?page=commande&action=my");
        exit;
    }


    public function delete()
    {
        Session::start();

        $this->requireRole([3]); // CLIENT uniquement

        $numero_commande = $_GET['id'];

        $commandeModel = new Commande();
        $commande = $commandeModel->getById($numero_commande);

        // sécurité métier
        if ($commande['statut'] == 'acceptée') {
            $_SESSION['error'] = "Impossible d'annuler une commande acceptée.";
            header("Location: ?page=commande&action=my");
            exit;
        }

        $commandeModel->deleteCommande($numero_commande);

        $_SESSION['success'] = "Commande annulée avec succès.";

        header("Location: ?page=commande&action=my");
        exit;
    }

    public function my()
    {
        Session::start();

        $this->requireRole([3]);

        if (!Session::isLogged()) {
            header("Location: ?page=login");
            exit;
        }

        $user_id = Session::userId();

        $commandeModel = new Commande();
        $commandes = $commandeModel->getCommandesByUser($user_id);

        // FLASH MESSAGES
        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;

        unset($_SESSION['success'], $_SESSION['error']);

        ob_start();
        require_once __DIR__ . '/../views/commande/my.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function updateStatus()
    {
        $this->requireRole([1, 2]);

        $numero_commande = $_POST['numero_commande'];
        $statut = $_POST['statut'];

        $commandeModel = new Commande();
        $commandeModel->updateStatut($numero_commande, $statut);

        header("Location: ?page=commande");
        exit;
    }

    public function editEmploye()
    {
        Session::start();

        $this->requireRole([1,2]);

        $numero_commande = $_GET['id'];

        $commandeModel = new Commande();

        $commande = $commandeModel->getById($numero_commande);

        ob_start();
        require_once __DIR__ . '/../views/commande/edit_employe.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function updateEmploye()
    {
        Session::start();

        $this->requireRole([1,2]);

        $numero_commande = $_POST['numero_commande'];

        $commandeModel = new Commande();

        $commande = $commandeModel->getById($numero_commande);

        $statut = $_POST['statut'];

        $commandeModel->updateCommande(
            $numero_commande,
            $commande['date_commande'],
            $commande['date_prestation'],
            $commande['heure_livraison'],
            $commande['prix_menu'],
            $commande['nombre_personne'],
            $commande['prix_livraison'],
            $statut,
            $commande['pret_materiel'],
            $commande['restitution_materiel'],
            $commande['user_id'],
            $commande['menu_id']
        );

        $_SESSION['success'] = "Statut mis à jour avec succès.";

        header("Location: ?page=commande");
        exit;
    }

}