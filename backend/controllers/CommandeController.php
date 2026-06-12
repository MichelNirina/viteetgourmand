<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../models/Menu.php';

class CommandeController extends ApiController
{
    public function index()
    {
        $this->requireRole([1, 2]);
        $this->json((new Commande())->getAllCommandes());
    }

    public function my()
    {
        $this->requireRole([3]);
        $this->json((new Commande())->getCommandesByUser(Session::userId()));
    }

    public function store()
    {
        $this->requireRole([1, 2, 3]);

        $menu_id         = (int)($_POST['menu_id']         ?? 0);
        $nombre_personne = (int)($_POST['nombre_personne'] ?? 0);
        $date_prestation = $_POST['date_prestation'] ?? null;
        $heure_livraison = $_POST['heure_livraison'] ?? null;

        if (!$menu_id || !$nombre_personne) {
            $this->error('Données manquantes', 400);
        }

        $menu = (new Menu())->getById($menu_id);
        if (!$menu) {
            $this->error('Menu introuvable', 404);
        }

        if ($nombre_personne < $menu['nombre_personne']) {
            $this->error('Minimum ' . $menu['nombre_personne'] . ' personnes requis', 400);
        }

        $prix_menu = $menu['prix_par_personne'] * $nombre_personne;
        if ($nombre_personne >= $menu['nombre_personne'] + 5) {
            $prix_menu *= 0.9;
        }

        (new Commande())->createCommande(
            uniqid('CMD'),
            date('Y-m-d'),
            $date_prestation,
            $heure_livraison,
            $prix_menu,
            $nombre_personne,
            5,
            'en attente',
            0, 0,
            Session::userId(),
            $menu_id
        );

        $this->json(['message' => 'Commande enregistrée avec succès'], 201);
    }

    public function update()
    {
        $this->requireRole([3]);

        $numero          = $_POST['numero_commande']  ?? null;
        $date_prestation = $_POST['date_prestation']  ?? null;
        $heure_livraison = $_POST['heure_livraison']  ?? null;
        $nombre_personne = (int)($_POST['nombre_personne'] ?? 0);

        if (!$numero) {
            $this->error('Numéro de commande manquant', 400);
        }

        $model   = new Commande();
        $commande = $model->getById($numero);

        if (!$commande) {
            $this->error('Commande introuvable', 404);
        }

        if ($commande['statut'] === 'acceptée') {
            $this->error('Cette commande ne peut plus être modifiée', 403);
        }

        $model->updateCommande(
            $numero,
            $commande['date_commande'],
            $date_prestation,
            $heure_livraison,
            $commande['prix_menu'],
            $nombre_personne,
            $commande['prix_livraison'],
            $commande['statut'],
            $commande['pret_materiel'],
            $commande['restitution_materiel'],
            $commande['user_id'],
            $commande['menu_id']
        );

        $this->json(['message' => 'Commande modifiée avec succès']);
    }

    public function delete()
    {
        $this->requireRole([3]);

        $numero   = $_GET['id'] ?? null;
        if (!$numero) {
            $this->error('ID manquant', 400);
        }

        $model   = new Commande();
        $commande = $model->getById($numero);

        if (!$commande) {
            $this->error('Commande introuvable', 404);
        }

        if ($commande['statut'] === 'acceptée') {
            $this->error('Impossible d\'annuler une commande acceptée', 403);
        }

        $model->deleteCommande($numero);
        $this->json(['message' => 'Commande annulée']);
    }

    public function updateEmploye()
    {
        $this->requireRole([1, 2]);

        $numero = $_POST['numero_commande'] ?? null;
        $statut = $_POST['statut']          ?? null;

        if (!$numero || !$statut) {
            $this->error('Données manquantes', 400);
        }

        $model    = new Commande();
        $commande = $model->getById($numero);

        if (!$commande) {
            $this->error('Commande introuvable', 404);
        }

        $model->updateCommande(
            $numero,
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

        $this->json(['message' => 'Statut mis à jour']);
    }
}
