<?php
require_once(__DIR__ . "/classes/helpers/CurlController.php");
require_once(__DIR__ . "/classes/helpers/RepositoryController.php");
require_once(__DIR__ . "/classes/controllers/UpdateController.php");
require_once(__DIR__ . "/classes/controllers/UserActionController.php");
require_once(__DIR__ . "/classes/controllers/LectureController.php");
require_once(__DIR__ . "/classes/models/StudentDetail.php");
require_once(__DIR__ . "/classes/models/Update.php");
require_once(__DIR__ . "/classes/models/Lecture.php");
require_once(__DIR__ . "/partials/error-display.php");

$curlController = new CurlController();
$output = $curlController->getHeader(URL);
$array = $curlController->deserializeHeader($output);
$curlController->closeUrl();

session_start();
if (isset($array["etag"])) {
    $updateController = new UpdateController();

    $update = $updateController->getByEtag(trim($array["etag"]));


    if (is_null($update)) {
        $update = new Update();
        $update->setEtag(trim($array["etag"]));

        $updateController->insertUpdate($update);

        $repoController = new RepositoryController();
        $repoController->updateRepository(URL . "/contents/");
        $diff = true;
    } else {
        $diff = false;
    }
} else {
    $diff = false;
}
$_SESSION["etag"] = trim($array["etag"]);
?>

<html lang="sk">
<head>
    <title>CURL - prednášky</title>
    <meta charset="UTF-8">
    <meta name="author" content="Juraj Lapčák">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="/CURL/assets/css/style.css" rel="stylesheet">
    <script src="/CURL/assets/js/script.js"></script>
</head>
<body>
<div class="container-fluid">
    <?php include(__DIR__ . "/partials/header.php"); ?>
    <div class="row mt-5">
        <div class="col-lg ">
            <main class="site-content">
                <div>
                    <?php echo "Bol vykonaný update dát? " . (($diff) ? "Áno" : "Nie"); ?>
                </div>
                <button class="btn btn-primary">
                    Update
                </button>
                <?php
                $userController = new UserActionController();
                $lectureController = new LectureController();
                $lectures = $lectureController->getLectures();
                $names = $userController->getAllNames();

                $lecturesCount = count($lectures);
                ?>
                <table class="table table-striped table-dark" id="students">
                    <thead>
                    <tr class="table-head">
                        <th scope="col" id="name">
                            Meno a priezvisko
                        </th>

                        <?php foreach ($lectures as $lecture) {
                            echo $lecture->getRowHead();
                        } ?>

                        <th scope="col" id="attendances">
                            Počet účastí
                        </th>
                        <th scope="col" id="minutes">
                            Počet minút na prednáškach
                        </th>
                    </tr>
                    </thead>
                    <tbody id="students-body">
                    <?php
                    foreach ($names as $name) {
//                        if (strcmp($name["name"], "Katarina Zakova") && strcmp($name["name"], "Matej Rábek") && strcmp($name["name"], "Michal Kocúr")) {
                            $studentDetail = $userController->getStudentDetail($name["name"]);
                            echo $studentDetail->getRow($lecturesCount);
//                        }
                    }
                    ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>
<?php include(__DIR__ . "/partials/footer.php"); ?>
</body>
</html>