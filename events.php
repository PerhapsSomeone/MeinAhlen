<html>
<head>
    <link rel="stylesheet" href="css/core.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
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

$futureEventsStmt = $pdo->prepare("SELECT * FROM `events` WHERE date >= NOW() ORDER BY date LIMIT 20;");
$pastEventsStmt = $pdo->prepare("SELECT * FROM `events` WHERE date < NOW() ORDER BY date DESC LIMIT 5;");

?>

<h1 class="centered page_text">Events in Ahlen</h1>
<br />
<p class="centered page_text">In Zukunft:</p>
<div class="centered page_text">
    <?php

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

    $futureEventsStmt->execute();
    $futureEvents = $futureEventsStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($futureEvents as $event) {
        echo "<hr />";
        echo "<p class='centered page_text'>".$event["name"]."</p>";
        echo "<p class='centered page_text'>".elipsis($event["description"], 30)."</p>";
        echo "<p class='centered page_text'> <i class=\"fas fa-fw fa-calendar-day centered\"></i>".date_format(new \DateTime($event["date"]), 'd.m.y')."</p>";
        echo "<a href='view_event.php?id=".$event["id"]."'>Mehr sehen</a>";
        echo "<hr />";
    }

    ?>

    <br />
    <br />

    <p class="centered page_text">Letzte Events: </p>

    <?php

    $pastEventsStmt->execute();
    $pastEvents = $pastEventsStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pastEvents as $event) {
        echo "<hr />";
        echo "<p class='centered page_text'>".$event["name"]."</p>";
        echo "<p class='centered page_text'>".elipsis($event["description"], 30)."</p>";
        echo "<p class='centered page_text'> <i class=\"fas fa-fw fa-calendar-day centered\"></i>".date_format(new \DateTime($event["date"]), 'd.m.y')."</p>";
        echo "<a href='view_event.php?id=".$event["id"]."'>Mehr sehen</a>";
        echo "<hr />";
    }

    ?>

</div>
</body>
</html>