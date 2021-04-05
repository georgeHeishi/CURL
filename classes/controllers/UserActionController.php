<?php
require_once(__DIR__ . "/../database/Database.php");
require_once(__DIR__ . "/../models/UserAction.php");
require_once(__DIR__ . "/../models/StudentDetail.php");

class UserActionController
{
    private ?PDO $conn;

    private $stm;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    /***********************************************************************************/
    public function prepareInsertQuery()
    {
        $this->stm = $this->conn->prepare("insert into user_actions (lecture_id, name, action, timestamp)
                                            values (:lecture_id, :name, :action, :timestamp)");
    }

    public function insertParams(UserAction $userAction): ?string
    {
        $lecture_id = $userAction->getLectureId();
        $name = $userAction->getName();
        $action = $userAction->getAction();
        $timestamp = $userAction->getTimestamp();

        $this->stm->bindParam(":lecture_id", $lecture_id);
        $this->stm->bindParam(":name", $name);
        $this->stm->bindParam(":action", $action);
        $this->stm->bindParam(":timestamp", $timestamp);

        try {
            $this->stm->execute();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            return null;
        }
    }


    /**********************************************************************************/
    public function truncate()
    {
        $stm = $this->conn->prepare("truncate table user_actions");
        $stm->execute();
    }


    public function getAllNames(): array
    {
        $stm = $this->conn->prepare("select name from user_actions group by name order by name");
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        return $stm->fetchAll();
    }

    public function getStudentDetail($name): StudentDetail
    {
        $stm = $this->conn->prepare("select lecture_id, action, timestamp from user_actions where name=:name order by lecture_id asc, timestamp asc ");
        $stm->bindParam(":name", $name);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stm->fetchAll();

        $user = new StudentDetail();
        $user->setName($name);

        $attendances = array();
        $lastId = 1;
        $lastAction = "";
        $lastTimestamp = "";

        //velmi skaredy kod
        //pardon
        //vytvori array so strukturou:

        //array => {
        //          [lecture_id] => {
        //                              [date(joined)] => date(left)
        //                               ...
        //                              [disconnected] => bool
        //                          }
        //          ...
        //}

        foreach ($result as $action) {
            if (intval($action["lecture_id"]) != $lastId) {

                if (!strcmp($lastAction, "Joined")) {
                    $attendances[$lastId][$lastTimestamp] = $this->getMaxTimeStamp($lastId);
                    $attendances[$lastId]["disconnected"] = false;
                } else {
                    $attendances[$lastId]["disconnected"] = true;
                }

                $lastId = intval($action["lecture_id"]);
            }

            if (!strcmp($action["action"], "Joined")) {
                $attendances[intval($action["lecture_id"])][$action["timestamp"]] = "";
                $lastAction = $action["action"];
                $lastTimestamp = $action["timestamp"];
            } else {
                if (!strcmp($lastAction, "Joined")) {
                    $attendances[intval($action["lecture_id"])][$lastTimestamp] = $action["timestamp"];
                }
                $lastAction = $action["action"];
                $lastTimestamp = $action["timestamp"];
            }
        }


        if (!strcmp($lastAction, "Joined")) {
            $attendances[$lastId][$lastTimestamp] = $this->getMaxTimeStamp($lastId);
            $attendances[$lastId]["disconnected"] = false;
        } else {
            $attendances[$lastId]["disconnected"] = true;

        }

        $user->setAttendance($attendances);


        return $user;
    }

    public function getMaxTimeStamp($lecture_id): ?string
    {
        $stm = $this->conn->prepare("select MAX(timestamp) as max from user_actions where lecture_id=:lecture_id");
        $stm->bindParam(":lecture_id", $lecture_id);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stm->fetch();
        if (!$result) {
            return null;
        } else {
            return $result["max"];
        }
    }

    public function getAttendanceCount($lecture_id): ?int
    {
        $stm = $this->conn->prepare("select count(*) as count
                                            from (
                                                    select name
                                                        from user_actions
                                                        where lecture_id = :lecture_id
                                                    group by  name
                                                ) as Z");
        $stm->bindParam(":lecture_id", $lecture_id, PDO::PARAM_INT);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stm->fetch();
        if (!$result) {
            return null;
        } else {
            return intval($result["count"]);
        }
    }
}