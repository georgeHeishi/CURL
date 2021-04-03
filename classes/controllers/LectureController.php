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

    public function insertLecture(Lecture $lecture): ?string
    {
        $stm = $this->conn->prepare("insert into lectures (timestamp)
                                        values (TIMESTAMP(:timestamp))");

        $timestamp = $lecture->getTimestamp();
        $stm->bindParam(":timestamp", $timestamp);
        try {
            $stm->execute();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {


            $stm = $this->conn->prepare("select id  from lectures where timestamp=TIMESTAMP(:timestamp)");
            $stm->bindParam(":timestamp", $timestamp);
            $stm->execute();
            $stm->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stm->fetch();
            return $result["id"];
        }
    }


    public function getLectures(): array
    {
        $stm = $this->conn->prepare("select * from lectures");
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_CLASS, "Lecture");
        return $stm->fetchAll();
    }
}