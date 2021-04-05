<?php
require_once(__DIR__ . "/../classes/controllers/UserActionController.php");
require_once(__DIR__ . "/../classes/controllers/LectureController.php");
require_once(__DIR__ . "/../classes/models/Lecture.php");

$lectureController = new LectureController();
$lectures = $lectureController->getLectures();

$userActionController = new UserActionController();

$result = array();

foreach ($lectures as $lecture){
    $id = $lecture->getId();
    $attendanceCount = $userActionController->getAttendanceCount($id);

    $result[$id] = $attendanceCount;
}

$response = array(
    "success" => true,
    "result" => $result
);

echo json_encode($response);