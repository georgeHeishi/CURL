<?php
require_once(__DIR__ . "/classes/helpers/CurlController.php");
require_once(__DIR__ . "/classes/helpers/RepositoryController.php");
require_once(__DIR__ . "/partials/error-display.php");

$curlController = new CurlController();
$output = $curlController->getHeader(URL);
$array = $curlController->deserializeHeader($output);
$curlController->closeUrl();

session_start();
if(isset($array["etag"])){
    if(!isset($_SESSION["etag"]) || strcmp($_SESSION["etag"],trim($array["etag"]))){
        $repoController = new RepositoryController();
        $repoController->updateRepository(URL . "/contents/");
        $diff = true;
        $_SESSION["etag"] = trim($array["etag"]);
    }else{
        $diff = false;
    }
}else{
    $diff = false;
}
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
                    <?php echo $_SESSION['etag']; ?>
                </div>
                <div>
                    <?php echo "Bol vykonaný update dát? " . (($diff) ? "Áno" : "Nie"); ?>
                </div>

            </main>
        </div>
    </div>
</div>
<?php include(__DIR__ . "/partials/footer.php"); ?>
</body>
</html>