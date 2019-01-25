<html>
<head>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/verticaltab.css">
    <link rel="stylesheet" href="css/core.css">
</head>
<body>
<?php readfile("layouts/navbar.html") ?>
<br />

<?php

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

function elipsis ($text, $words = 15) {
    // Check if string has more than X words
    if (str_word_count($text) > $words) {

        // Extract first X words from string
        preg_match("/(?:[^\s,\.;\?\!]+(?:[\s,\.;\?\!]+|$)){0,$words}/", $text, $matches);
        $text = trim($matches[0]);

        // Let's check if it ends in a comma or a dot.
        if (substr($text, -1) == ',') {
            // If it's a comma, let's remove it and add a ellipsis
            $text = rtrim($text, ',');
            $text .= ' [mehr lesen]';
        } else if (substr($text, -1) == '.') {
            // If it's a dot, let's remove it and add a ellipsis (optional)
            $text = rtrim($text, '.');
            $text .= ' [mehr lesen]';
        } else {
            // Doesn't end in dot or comma, just adding ellipsis here
            $text .= ' [mehr lesen]';
        }
    }
    // Returns "ellipsed" text, or just the string, if it's less than X words wide.
    return $text;
}

?>

<img src="assets/img/rsz_1ahlen_header_c_stadt_ahlen-1800x900.png" width="100%" />

<div class="container">
    <div class="vertical-tabs">
        <ul class="nav nav-tabs" role="tablist">

            <?php

            $stmt = $pdo->prepare("SELECT * FROM locations ORDER BY RAND() LIMIT 5");
            $stmt->execute([]);
            $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $active_location = $locations[0];
            unset($locations[0]);

            //var_dump($locations);

            $i = 2;

            echo "<li class='nav-item'><a class=\"nav-link active\" data-toggle=\"tab\" href='#pag1' role=\"tab\" aria-controls=\"home\">" . $active_location["name"] . "</a></li>";

            foreach ($locations as $location) {
                echo "<li class='nav-item'><a class=\"nav-link\" data-toggle=\"tab\" href='#pag".$i."' role=\"tab\" aria-controls=\"home\">" . $location["name"] . "</a></li>";
                $i++;
            }
            echo "</ul><div class=\"tab-content\">";

            echo "<div class=\"tab-pane active\" id='pag1' role=\"tabpanel\"> <div class=\"sv-tab-panel\"> <h3>".$active_location["name"]."</h3> <p>".elipsis($active_location["description"], 10)."<br /><a href='view.php?id=".$active_location["id"]."'>Mehr sehen</a></p> </div> </div>";

            $i = 2;

            foreach ($locations as $location) {
                echo "<div class=\"tab-pane\" id='pag".$i."' role=\"tabpanel\"> <div class=\"sv-tab-panel\"> <h3>".$location["name"]."</h3> <p>".elipsis($location["description"], 10)."<br /><a href='view.php?id=".$location["id"]."'>Details sehen</a></p> </div> </div>";
                $i++;
            }

            echo "<p>Alle Orte sehen</p>";

            echo "</div></div></div>";

            ?>
            <a style="margin-top: 0" href="list.php" class="centered page_text"><p class="centered page_text">Alle Orte sehen</p></a>
</body>
</html>