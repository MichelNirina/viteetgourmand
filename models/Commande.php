<?php

require_once __DIR__ . '/../core/Model.php';

class Commande extends Model
{
    public function getAllCommandes()
    {
        $sql = "SELECT 
                    commande.*,
                    utilisateur.prenom AS client_prenom,
                    menu.titre AS menu_titre
                FROM commande
                JOIN utilisateur ON commande.user_id = utilisateur.user_id
                JOIN menu ON commande.menu_id = menu.menu_id
                ORDER BY commande.date_commande DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($numero_commande)
    {
        $sql = "SELECT * FROM commande
                WHERE numero_commande = :numero_commande";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':numero_commande' => $numero_commande
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCommande(
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
    )
    {
        $sql = "INSERT INTO commande (
                    numero_commande,
                    date_commande,
                    date_prestation,
                    heure_livraison,
                    prix_menu,
                    nombre_personne,
                    prix_livraison,
                    statut,
                    pret_materiel,
                    restitution_materiel,
                    user_id,
                    menu_id
                )
                VALUES (
                    :numero_commande,
                    :date_commande,
                    :date_prestation,
                    :heure_livraison,
                    :prix_menu,
                    :nombre_personne,
                    :prix_livraison,
                    :statut,
                    :pret_materiel,
                    :restitution_materiel,
                    :user_id,
                    :menu_id
                )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':numero_commande' => $numero_commande,
            ':date_commande' => $date_commande,
            ':date_prestation' => $date_prestation,
            ':heure_livraison' => $heure_livraison,
            ':prix_menu' => $prix_menu,
            ':nombre_personne' => $nombre_personne,
            ':prix_livraison' => $prix_livraison,
            ':statut' => $statut,
            ':pret_materiel' => $pret_materiel,
            ':restitution_materiel' => $restitution_materiel,
            ':user_id' => $user_id,
            ':menu_id' => $menu_id
        ]);
    }

    public function updateCommande(
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
    )
    {
        $sql = "UPDATE commande SET
                    date_commande = :date_commande,
                    date_prestation = :date_prestation,
                    heure_livraison = :heure_livraison,
                    prix_menu = :prix_menu,
                    nombre_personne = :nombre_personne,
                    prix_livraison = :prix_livraison,
                    statut = :statut,
                    pret_materiel = :pret_materiel,
                    restitution_materiel = :restitution_materiel,
                    user_id = :user_id,
                    menu_id = :menu_id
                WHERE numero_commande = :numero_commande";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':numero_commande' => $numero_commande,
            ':date_commande' => $date_commande,
            ':date_prestation' => $date_prestation,
            ':heure_livraison' => $heure_livraison,
            ':prix_menu' => $prix_menu,
            ':nombre_personne' => $nombre_personne,
            ':prix_livraison' => $prix_livraison,
            ':statut' => $statut,
            ':pret_materiel' => $pret_materiel,
            ':restitution_materiel' => $restitution_materiel,
            ':user_id' => $user_id,
            ':menu_id' => $menu_id
        ]);
    }

    public function deleteCommande($numero_commande)
    {
        $sql = "DELETE FROM commande
                WHERE numero_commande = :numero_commande";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':numero_commande' => $numero_commande
        ]);
    }

    public function getCommandesByUser($user_id)
    {
        $sql = "SELECT c.*, m.titre
                FROM commande c
                INNER JOIN menu m ON c.menu_id = m.menu_id
                WHERE c.user_id = :user_id
                ORDER BY c.date_commande DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatut($numero_commande, $statut)
    {
        $sql = "UPDATE commande SET statut = :statut WHERE numero_commande = :numero_commande";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':statut' => $statut,
            ':numero_commande' => $numero_commande
        ]);
    }

    public function countCommandes()
    {
        return $this->conn->query("SELECT COUNT(*) as total FROM commande")
            ->fetch(PDO::FETCH_ASSOC);
    }

    public function getStatsByMenu()
    {
        $sql = "SELECT menu_id, COUNT(*) as total
                FROM commande
                GROUP BY menu_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCAByMenu()
    {
        $sql = "SELECT menu_id, SUM(prix_menu) as ca
                FROM commande
                GROUP BY menu_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}