<?php
require_once(__DIR__ . "/../database/Database.php");
require_once(__DIR__ . "/../models/UserAction.php");

class UserActionController
{
    private ?PDO $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

//    https://stackoverflow.com/questions/4205181/insert-into-a-mysql-table-or-update-if-exists
    public function insertUserAction(UserAction $userAction): ?string{
        $stm = $this->conn->prepare("insert into user_actions (lecture_id, name, action, timestamp)
                                            values (:lecture_id, :name, :action, :timestamp)
                                            ON DUPLICATE KEY UPDATE lecture_id=:lecture_id, name=:name, action=:action, timestamp=:timestamp");

        $lecture_id = $userAction->getLectureId();
        $name = $userAction->getName();
        $action = $userAction->getAction();
        $timestamp = $userAction->getTimestamp();

        $stm->bindParam(":lecture_id", $lecture_id);
        $stm->bindParam(":name", $name);
        $stm->bindParam(":action", $action);
        $stm->bindParam(":timestamp", $timestamp);

        try {
            $stm->execute();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {


            return null;
        }
    }

    public function truncate(){
        $stm = $this->conn->prepare("truncate table user_actions");
        $stm->execute();
    }

}