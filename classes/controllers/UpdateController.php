<?php
require_once(__DIR__ . "/../models/Update.php");

class UpdateController
{
    private ?PDO $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function getByEtag(string $etag):?Update
    {
        $stm = $this->conn->prepare("select * from updates where etag=:etag");

        $stm->bindParam(":etag", $etag);

        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_CLASS, "Update");

        $result = $stm->fetch();

        if(!$result){
            return null;
        }else{
            return $result;
        }
    }

    public function insertUpdate(Update $update)
    {
        $stm = $this->conn->prepare("insert into updates (etag, timestamp)
                                            values (:etag, NOW())");
        $etag = $update->getEtag();

        $stm->bindParam(":etag", $etag);

        try {
            $stm->execute();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            return null;
        }
    }
}