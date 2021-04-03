<?php
require_once(__DIR__ . "/../helpers/CurlController.php");
require_once(__DIR__ . "/../controllers/LectureController.php");
require_once(__DIR__ . "/../controllers/UserActionController.php");
require_once(__DIR__ . "/../models/Lecture.php");
require_once(__DIR__ . "/../models/UserAction.php");
require_once __DIR__ . "/../../config.php";


class RepositoryController
{
    public function updateRepository($repositoryUrl)
    {
        $curlController = new CurlController();
        $userActionController = new UserActionController();
        $userActionController->truncate();

        $repoContent = $curlController->getContent($repositoryUrl);
        $repoContent = json_decode($repoContent);

        $userActionController->prepareInsertQuery();

        foreach ($repoContent as $file) {
            $this->updateFile($userActionController, $file->path);
        }
        $curlController->closeUrl();
    }

    public function updateFile($userActionController, $fileUrl)
    {

        $curlController = new CurlController();
        $lectureController = new LectureController();
        $lecture = new Lecture();
        $lecture->setTimestamp($this->extractTimestamp($fileUrl));

        $id = $lectureController->insertLecture($lecture);

        $output = $curlController->getFileContent(REPO_URL . $fileUrl);

        if (!mb_detect_encoding($output, 'UTF-8', true)) {
            $output = mb_convert_encoding($output, 'UTF-8', 'UTF-16LE');
        }
        $array = $curlController->deserializeContent($output);

        foreach ($array as $user) {
            $user->setLectureId(intval($id));
            $userActionController->insertParams($user);
        }

        $curlController->closeUrl();
    }

    public function extractTimestamp($fileUrl): bool|string
    {
        $pos = strpos($fileUrl, "_");
        return substr($fileUrl, 0, $pos);
    }
}