<?php

require_once __DIR__ . '/../core/Model.php';

class Role extends Model
{
    public function getAllRoles()
    {
        $sql = "SELECT * FROM role";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM role WHERE role_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createRole($libelle)
    {
        $sql = "INSERT INTO role (libelle)
                VALUES (:libelle)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':libelle' => $libelle
        ]);
    }

    public function updateRole($id, $libelle)
    {
        $sql = "UPDATE role
                SET libelle = :libelle
                WHERE role_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':libelle' => $libelle
        ]);
    }

    public function deleteRole($id)
    {
        $sql = "DELETE FROM role
                WHERE role_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);
    }
}