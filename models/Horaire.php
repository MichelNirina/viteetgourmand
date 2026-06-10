<?php

require_once __DIR__ . '/../core/Model.php';

class Horaire extends Model
{
    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM horaire");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM horaire WHERE horaire_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($jour, $ouverture, $fermeture)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO horaire(jour, heure_ouverture, heure_fermeture)
             VALUES(:jour, :o, :f)"
        );

        $stmt->execute([
            ':jour' => $jour,
            ':o' => $ouverture,
            ':f' => $fermeture
        ]);
    }

    public function update($id, $jour, $o, $f)
    {
        $stmt = $this->conn->prepare(
            "UPDATE horaire
             SET jour = :jour,
                 heure_ouverture = :o,
                 heure_fermeture = :f
             WHERE horaire_id = :id"
        );

        $stmt->execute([
            ':id' => $id,
            ':jour' => $jour,
            ':o' => $o,
            ':f' => $f
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM horaire WHERE horaire_id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function getGroupedByDay()
    {
        $stmt = $this->conn->prepare(
            "SELECT jour, GROUP_CONCAT(
                CASE
                    WHEN heure_ouverture IS NULL THEN 'Fermé'
                    ELSE CONCAT(heure_ouverture, ' - ', heure_fermeture)
                END
                ORDER BY heure_ouverture
                SEPARATOR ' / '
            ) AS creneaux
            FROM horaire
            GROUP BY jour
            ORDER BY FIELD(jour,
                'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'
            )"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}