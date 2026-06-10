<?php

require_once __DIR__ . '/../core/Model.php';

class Allergene extends Model
{
    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM allergene");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM allergene WHERE allergene_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createAllergene($libelle)
    {
        $stmt = $this->conn->prepare("INSERT INTO allergene(libelle) VALUES(:libelle)");
        $stmt->execute([':libelle' => $libelle]);
    }

    public function updateAllergene($id, $libelle)
    {
        $stmt = $this->conn->prepare("
            UPDATE allergene
            SET libelle = :libelle
            WHERE allergene_id = :id
        ");

        $stmt->execute([
            ':id' => $id,
            ':libelle' => $libelle
        ]);
    }

    public function deleteAllergene($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM allergene WHERE allergene_id = :id");
        $stmt->execute([':id' => $id]);
    }
}