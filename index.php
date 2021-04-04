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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>


    <link href="/CURL/assets/css/style.css" rel="stylesheet">
    <script src="/CURL/assets/js/script.js"></script>
    <script src="/CURL/assets/js/jquery-script.js"></script>
</head>
<body>
<div class="container-fluid">
    <?php include(__DIR__ . "/partials/header.php"); ?>
    <div class="row mt-5">
        <div class="col-lg ">
            <main class="site-content">
                <div class="row mt-2">
                    <div>
                        <?php echo "Bol vykonaný update dát? " . (($diff) ? "Áno" : "Nie"); ?>
                    </div>
                </div>
                <?php
                $userController = new UserActionController();
                $lectureController = new LectureController();
                $lectures = $lectureController->getLectures();
                $names = $userController->getAllNames();

                $lecturesCount = count($lectures);
                ?>
                <div class="row mt-4">

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
                            $studentDetail = $userController->getStudentDetail($name["name"]);
                            echo $studentDetail->getRow($lecturesCount);
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
            </div>
            <div class="modal-body">
                <h6 class="modal-title" id="lecture-title">Prednáška č. </h6>
                <br>
                <table class="table table-striped table-dark">
                    <thead>
                    <tr class="table-head">
                        <th scope="col" id="joined">
                            Pripojil
                        </th>
                        <th scope="col" id="left">
                            Odpojil
                        </th>
                    </tr>
                    </thead>
                    <tbody id="attendance-body">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include(__DIR__ . "/partials/footer.php"); ?>
</body>
</html>