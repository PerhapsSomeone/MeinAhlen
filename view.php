<html>
<head>
    <link rel="stylesheet" href="css/core.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA316TXyYC1uWRw1cZc0b7G6tJQgEXeUg8" data-cfasync="false"></script>
</head>
<body>
<?php readfile("layouts/navbar.html") ?>
<br />
<?php
    if(!isset($_GET["id"]) || empty($_GET["id"]) || !is_numeric($_GET["id"])) {
        die("<h1 class='centered page_text'>Kein Ort ausgewählt!</h1><br /><a class='page_text centered' href='index.php'><p class='page_text centered'>Zurück</p></a>");
    }

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$host = '127.0.0.1';
$db   = 'meinahlen';
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}


$stmt = $pdo->prepare("SELECT * FROM locations WHERE id = ?");
$stmt->execute([$_GET["id"]]);
$res = $stmt->fetch(PDO::FETCH_ASSOC);
?>



<h1 class="centered page_text"><?= $res["name"] ?></h1>

<br />

<p class="centered page_text"><?= $res["description"] ?></p>
<br />
<p class="centered page_text">Ort:</p>
<div id="googleMap" class="centered mx-auto d-block" style="width:600px; height:400px;"></div>

<script>
    function initMap() {
        var mapProp= {
            center:new google.maps.LatLng(<?php echo $res["xCoord"].", ".$res["yCoord"]; ?>),
            zoom: 17,
        };
        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
        map.setZoom(17);

        new google.maps.Marker({
            position : new google.maps.LatLng(<?php echo $res["xCoord"].", ".$res["yCoord"]; ?>),
            map : map
        });
    }

    initMap();
</script>

</body>
</html>