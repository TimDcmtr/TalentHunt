<?php
// config/Database.php

class Database {
    // Paramètres de la BDD externe
    private $host = "panel.lemecha.fr:3307"; // IP de ton serveur BDD externe
    private $db_name = "talenthub";
    private $username = "admin";
    private $pass = "admin";
    public $conn;

    // Connexion à la base de données
    public function getConnection() {
        $this->conn = null;

        try {
            // DSN (Data Source Name)
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            
            $this->conn = new PDO($dsn, $this->username, $this->pass);
            
            // Configuration des erreurs : on veut des Exceptions
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Mode de fetch par défaut (Tableau associatif)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>