<?php

require_once __DIR__ . '/../core/Model.php';

class Plat extends Model
{
    public function getAll()
    {
        $sql = "
            SELECT 
                p.plat_id,
                p.titre_plat,
                p.photo,
                p.menu_id,
                GROUP_CONCAT(a.libelle SEPARATOR ', ') AS allergenes
            FROM plat p
            LEFT JOIN contient c ON p.plat_id = c.plat_id
            LEFT JOIN allergene a ON a.allergene_id = c.allergene_id
            GROUP BY p.plat_id
        ";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "
            SELECT *
            FROM plat
            WHERE plat_id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByMenuId($menu_id)
    {
        $sql = "SELECT * FROM plat WHERE menu_id = :menu_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':menu_id' => $menu_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($titre_plat, $photo, $menu_id)
    {
        $sql = "INSERT INTO plat (
                    titre_plat,
                    photo,
                    menu_id
                )
                VALUES (
                    :titre_plat,
                    :photo,
                    :menu_id
                )";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':titre_plat' => $titre_plat,
            ':photo' => $photo,
            ':menu_id' => (int)$menu_id
        ]);

        return $this->conn->lastInsertId();
    }

    public function update($id, $titre_plat, $photo, $menu_id)
    {
        $stmt = $this->conn->prepare(
            "UPDATE plat
             SET titre_plat = :titre_plat,
                 photo = :photo,
                 menu_id = :menu_id
             WHERE plat_id = :id"
        );

        $stmt->execute([
            ':id' => $id,
            ':titre_plat' => $titre_plat,
            ':photo' => $photo,
            ':menu_id' => (int)$menu_id
        ]);
    }

    public function addAllergene($plat_id, $allergene_id)
    {
        $sql = "INSERT INTO contient (
                    plat_id,
                    allergene_id
                )
                VALUES (
                    :plat_id,
                    :allergene_id
                )";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':plat_id' => $plat_id,
            ':allergene_id' => $allergene_id
        ]);
    }

    public function deleteAllergenes($plat_id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM contient
             WHERE plat_id = :plat_id"
        );

        $stmt->execute([
            ':plat_id' => $plat_id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM contient
             WHERE plat_id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $stmt = $this->conn->prepare(
            "DELETE FROM plat
             WHERE plat_id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);
    }

    public function getAllergenesByPlat($plat_id)
    {
        $sql = "
            SELECT a.*
            FROM allergene a
            INNER JOIN contient c
                ON a.allergene_id = c.allergene_id
            WHERE c.plat_id = :plat_id
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':plat_id' => $plat_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}