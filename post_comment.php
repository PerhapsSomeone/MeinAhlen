<?php

if(!isset($_POST["content"]) || empty($_POST["content"]) || strlen($_POST["content"]) > 3000 || !isset($_POST["article_id"]) || !is_numeric($_POST["article_id"])) {
    echo "<script>window.history.back();</script>";
}

if(empty($_POST["username"])) {
    $_POST["username"] = "Anonym";
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

echo $_POST["article_id"];

var_dump($_POST);

echo is_numeric($_POST["article_id"]);

$stmt = $pdo->prepare("INSERT INTO `comments` (`id`, `parent_article`, `date`, `content`, `username`) VALUES (NULL, ?, CURRENT_TIMESTAMP, ?, ?)");
$stmt->execute(array($_POST["article_id"], $_POST["content"], $_POST["username"]));

header("Location: view_event.php?id=".$_POST["article_id"]);