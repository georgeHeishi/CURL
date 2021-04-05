<?php
require_once(__DIR__ . "/../database/Database.php");
require_once(__DIR__ . "/../models/Lecture.php");

class LectureController
{
    private ?PDO $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function insertLecture(Lecture $lecture): int
    {
        $stm = $this->conn->prepare("insert into lectures (id,timestamp)
                                        values (:id,TIMESTAMP(:timestamp))");

        $timestamp = $lecture->getTimestamp();
        $id = $lecture->getId();

        $stm->bindParam(":id", $id, PDO::PARAM_INT);
        $stm->bindParam(":timestamp", $timestamp);

        try {
            $stm->execute();
            return intval($id);
        } catch (Exception $e) {
            echo $e->getMessage();
            $stm = $this->conn->prepare("select id  from lectures where timestamp=TIMESTAMP(:timestamp)");
            $stm->bindParam(":timestamp", $timestamp);
            $stm->execute();
            $stm->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stm->fetch();

            return intval($result["id"]);
        }
    }

    public function getLectures(): array
    {
        $stm = $this->conn->prepare("select * from lectures");
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_CLASS, "Lecture");
        return $stm->fetchAll();
    }

    public function truncate()
    {
        $stm = $this->conn->query("set foreign_key_checks = 0");
        $stm = $this->conn->prepare("truncate table lectures");
        $stm->execute();
    }
}