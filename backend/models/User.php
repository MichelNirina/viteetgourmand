<?php

require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    // LOGIN
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // READ ALL
    public function getAllUsers()
    {
        $sql = "SELECT u.*, r.libelle 
                FROM utilisateur u
                LEFT JOIN role r ON u.role_id = r.role_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function createUser($email, $password, $prenom, $telephone, $ville, $pays, $adresse, $role_id)
    {
        $sql = "INSERT INTO utilisateur 
                (email, password, prenom, telephone, ville, pays, adresse_postale, role_id)
                VALUES 
                (:email, :password, :prenom, :telephone, :ville, :pays, :adresse, :role_id)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':email' => $email,
            ':password' => $password,
            ':prenom' => $prenom,
            ':telephone' => $telephone,
            ':ville' => $ville,
            ':pays' => $pays,
            ':adresse' => $adresse,
            ':role_id' => $role_id
        ]);
    }

    // GET BY ID
    public function getById($id)
    {
        $sql = "SELECT * FROM utilisateur WHERE user_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function updateUser($id, $prenom, $email, $telephone, $ville, $pays, $adresse_postale)
    {
        $sql = "UPDATE utilisateur 
                SET prenom = :prenom,
                    email = :email,
                    telephone = :telephone,
                    ville = :ville,
                    pays = :pays,
                    adresse_postale = :adresse_postale
                WHERE user_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':ville' => $ville,
            ':pays' => $pays,
            ':adresse_postale' => $adresse_postale
        ]);
    }
    // DELETE
    public function deleteUser($id)
    {
        $sql = "DELETE FROM utilisateur WHERE user_id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getEmployees()
    {
        $sql = "SELECT * FROM utilisateur WHERE role_id = 2";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}