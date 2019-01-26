<html>
<head>
    <link rel="stylesheet" href="css/core.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <link rel="stylesheet" href="css/navbar.css">
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


$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$_GET["id"]]);
$res = $stmt->fetch(PDO::FETCH_ASSOC);

$commentStmt = $pdo->prepare("SELECT * FROM comments WHERE parent_article = ? ORDER BY date DESC;");
$commentStmt->execute([$_GET["id"]]);

$comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);


?>


<h1 class="centered page_text">Event: <?= $res["name"] ?></h1>

<br />

<p class="centered page_text">Beschreibung: <br /><?= $res["description"] ?></p>

<?php echo "<p class='centered page_text'> <i class=\"fas fa-fw fa-calendar-day centered\"></i>".date_format(new \DateTime($res["date"]), 'd.m.y')."</p>"; ?>
<br />

<p class="page_text centered">Möchtest du etwas sagen? Das Event bewerten?</p>
<p class="page_text centered">Hinterlasse einfach einen Kommentar!</p>

<div class="card w-75 centered mx-auto page_text">
    <h5 class="card-header">Kommentar hinzufügen</h5>
    <div class="card-body page_text">
        <form action="post_comment.php" id="addCommentForm" method="post">
            <input name="username" type="text" class="input is-rounded" placeholder="Nutzername (optional)">
            <br /><br />
            <textarea name="content" form="addCommentForm" class="textarea is-rounded" placeholder="Schreibe hier deinen Kommentar... (max. 3000 Zeichen)"></textarea>
            <br />
            <input type="hidden" value=<?= $_GET["id"] ?> name="article_id">
            <input type="submit" class="button is-rounded is-success" value="Absenden">
        </form>
    </div>
</div>

<br />

<div class="card w-75 mx-auto page_text">
    <h5 class="card-header">Kommentare (<?= sizeof($comments) ?>)</h5>
    <div class="card-body page_text">
        <?php
            if(sizeof($comments) < 1) {
                echo "<p>Es sind noch keine Kommentare vorhanden.</p>";
            } else {
                foreach ($comments as $comment) {
                    echo "<p class='page_text'>".$comment["username"]." schrieb am ".date_format(new \DateTime($comment["date"]), "d.m.y")." um ".
                        date_format(new \DateTime($comment["date"]), "h:s")." Uhr:</p>";

                    echo "<p class='page_text'>".htmlspecialchars($comment["content"], ENT_QUOTES, 'UTF-8')."</p><hr />";

                }
            }
        ?>
    </div>
</div>

</body>
</html>