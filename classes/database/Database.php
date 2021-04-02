<?php

require_once __DIR__ . "/../../config.php";

class Database
{
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->conn->exec('set names utf8');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
            echo 'Database could not be connected' . $e->getMessage();
        }
        return $this->conn;
    }
}