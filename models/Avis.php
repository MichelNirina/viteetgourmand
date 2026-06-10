<?php

require_once __DIR__ . '/../core/Model.php';

class Avis extends Model
{
    public function getAllAvis()
    {
        $sql = "
            SELECT avis.*,
                   utilisateur.prenom,
                   utilisateur.email
            FROM avis
            LEFT JOIN utilisateur ON avis.user_id = utilisateur.user_id
            ORDER BY avis.avis_id DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM avis WHERE avis_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createAvis($note, $description, $user_id)
    {
        $sql = "
            INSERT INTO avis (note, description, statut, user_id)
            VALUES (:note, :description, :statut, :user_id)
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':note' => $note,
            ':description' => $description,
            ':statut' => 'en_attente',
            ':user_id' => $user_id
        ]);
    }

    public function updateAvis($id, $note, $description, $statut)
    {
        $sql = "
            UPDATE avis
            SET note = :note,
                description = :description,
                statut = :statut
            WHERE avis_id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':note' => $note,
            ':description' => $description,
            ':statut' => $statut
        ]);
    }

    public function deleteAvis($id)
    {
        $sql = "DELETE FROM avis WHERE avis_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

  
    public function getValidatedAvis()
    {
        $sql = "
            SELECT avis.*,
                   utilisateur.prenom
            FROM avis
            LEFT JOIN utilisateur ON avis.user_id = utilisateur.user_id
            WHERE avis.statut = 'valide'
            ORDER BY avis.avis_id DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatut($id, $statut)
    {
        $sql = "UPDATE avis SET statut = :statut WHERE avis_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':statut' => $statut
        ]);
    }
}