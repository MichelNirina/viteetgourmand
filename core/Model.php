<?php

require_once __DIR__ . "/../config/database.php";

class Model {

    protected $conn;

    public function __construct() {

        // création connexion DB
        $db = new Database();
        $this->conn = $db->getConnection();
    }
}