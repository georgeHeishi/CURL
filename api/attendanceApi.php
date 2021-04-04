<?php
require_once(__DIR__ . "/../classes/controllers/UserActionController.php");
require_once(__DIR__ . "/../classes/controllers/LectureController.php");
require_once(__DIR__ . "/../classes/models/Lecture.php");
require_once(__DIR__ . "/../classes/models/StudentDetail.php");


// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

if (isset($data)) {
    $lecture_id = $data->lecture_id;
    $name = $data->name;
    if (isset($lecture_id) && isset($name)) {
        $userController = new UserActionController();
        $lectureController = new LectureController();
        $lectures = $lectureController->getLectures();

        $lecturesCount = count($lectures);

        $studentDetail = $userController->getStudentDetail($name);
        $result = $studentDetail->getAttendanceDetail($lecture_id);

        $response = array(
            "name" => $name,
            "lecture_id" => $lecture_id,
            "result" => $result
        );

        echo json_encode($response);
    }
}