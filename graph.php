<html lang="sk">
<head>
    <title>CURL - prednášky</title>
    <meta charset="UTF-8">
    <meta name="author" content="Juraj Lapčák">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <link href="/CURL/assets/css/style.css" rel="stylesheet">

    <!-- PLOTLY -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

    <script src="/CURL/assets/js/plot.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row mt-3 mb-1">
        <header class="col-lg mb-2 site-header">
            <div class="mt-2 d-flex flex-row">
                <div class="p-2 flex-column" style="margin-left: 5%">
                    <a href="https://wt98.fei.stuba.sk/CURL/"><h1 id="main-branding">Dochádzka</h1></a>
                </div>
            </div>
            <div class="mb-2 p-2 flex-row" style="margin-left: 5%">
                <a href="https://wt98.fei.stuba.sk/CURL/graph"><h1 id="main-branding">Graf</h1></a>
            </div>
        </header>
    </div>
    <div class="row mt-5">
        <div class="col-lg ">
            <main class="site-content">
                <div id='plot'><!-- Plotly chart will be drawn inside this DIV -->
            </main>
        </div>
    </div>

    <?php include(__DIR__ . "/partials/footer.php"); ?>
</body>
</html>