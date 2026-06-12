<?php

require_once __DIR__ . "/../core/Model.php";

class Menu extends Model
{
    public function getAllMenus()
    {
        $sql = "SELECT * FROM menu";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createMenu(
        $titre,
        $description,
        $nombre_personne,
        $prix_par_personne,
        $quantite_restante,
        $regime_id,
        $theme_id
    )
    {
        $sql = "INSERT INTO menu (
                    titre,
                    description,
                    nombre_personne,
                    prix_par_personne,
                    quantite_restante,
                    regime_id,
                    theme_id
                )
                VALUES (
                    :titre,
                    :description,
                    :nombre_personne,
                    :prix_par_personne,
                    :quantite_restante,
                    :regime_id,
                    :theme_id
                )";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':nombre_personne' => $nombre_personne,
            ':prix_par_personne' => $prix_par_personne,
            ':quantite_restante' => $quantite_restante,
            ':regime_id' => $regime_id,
            ':theme_id' => $theme_id
        ]);
    }

    public function getById($id)
    {
        $sql = "
            SELECT menu.*,
                   theme.libelle AS theme_libelle,
                   regime.libelle AS regime_libelle
            FROM menu
            LEFT JOIN theme ON menu.theme_id = theme.theme_id
            LEFT JOIN regime ON menu.regime_id = regime.regime_id
            WHERE menu.menu_id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMenu($id, $titre, $description, $nombre_personne, $prix_par_personne, $quantite_restante, $regime_id, $theme_id)
    {
        $sql = "UPDATE menu SET
                    titre = :titre,
                    description = :description,
                    nombre_personne = :nombre_personne,
                    prix_par_personne = :prix_par_personne,
                    quantite_restante = :quantite_restante,
                    regime_id = :regime_id,
                    theme_id = :theme_id
                WHERE menu_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':titre' => $titre,
            ':description' => $description,
            ':nombre_personne' => $nombre_personne,
            ':prix_par_personne' => $prix_par_personne,
            ':quantite_restante' => $quantite_restante,
            ':regime_id' => $regime_id,
            ':theme_id' => $theme_id
        ]);
    }

    public function deleteMenu($id)
    {
        $sql = "DELETE FROM menu WHERE menu_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function filterMenus($prix_min = null, $prix_max = null, $theme = null, $regime = null, $personnes = null)
    {
        $sql = "
            SELECT menu.*,
                   theme.libelle AS theme_libelle,
                   regime.libelle AS regime_libelle,
                   (SELECT photo FROM plat WHERE plat.menu_id = menu.menu_id LIMIT 1) AS photo_preview
            FROM menu
            LEFT JOIN theme ON menu.theme_id = theme.theme_id
            LEFT JOIN regime ON menu.regime_id = regime.regime_id
            WHERE 1
        ";

        $params = [];

        if (!empty($prix_min)) {
            $sql .= " AND prix_par_personne >= :prix_min";
            $params[':prix_min'] = $prix_min;
        }

        if (!empty($prix_max)) {
            $sql .= " AND prix_par_personne <= :prix_max";
            $params[':prix_max'] = $prix_max;
        }

        if (!empty($theme)) {
            $sql .= " AND theme.libelle = :theme";
            $params[':theme'] = $theme;
        }

        if (!empty($regime)) {
            $sql .= " AND regime.libelle = :regime";
            $params[':regime'] = $regime;
        }

        if (!empty($personnes)) {
            $sql .= " AND nombre_personne >= :personnes";
            $params[':personnes'] = $personnes;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "
            SELECT menu.*,
                   theme.libelle AS theme_libelle,
                   regime.libelle AS regime_libelle
            FROM menu
            LEFT JOIN theme ON menu.theme_id = theme.theme_id
            LEFT JOIN regime ON menu.regime_id = regime.regime_id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}