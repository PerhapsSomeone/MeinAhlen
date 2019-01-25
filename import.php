<?php

$json = "{
  \"xCoord\": [51.7649633, 51.7631611, 51.764013, 51.76139, 51.7618751, 51.7634306, 51.7608869, 51.7605818, 51.7632124, 51.763321, 51.763051, 51.763300, 51.778745, 51.774339, 51.75552],
  \"yCoord\": [7.9114266, 7.9044317, 7.908644, 7.8875609, 7.8879231, 7.8830512, 7.9079306, 7.9083821, 7.8972585, 7.890339, 7.894303, 7.8885423, 7.892610, 7.890501, 7.895168],
  \"title\": [\"Mc. Donalds\", \"Royal Kebap Haus\", \"Pizzaservice Don Camillo\", \"Stadtbücherei\", \"CinemAhlen\", \"Edeka Milkner\", \"Edeka Wiewel\", \"Rossman\", \"Rossman\", \"Wochenmarkt\", \"dm-Markt\", \"expert Promedia\", \"Sportpark Nord\", \"Freibad Ahlen\", \"Parkbad\"],
  \"sDesc\": [\"Restaurant\", \"Restaurant\", \"Restaurant\", \"Kultur\", \"Unterhaltung\", \"Läden\", \"Läden\", \"Läden\", \"Läden\", \"Events\", \"Läden\", \"Läden\", \"Sport\", \"Sport\", \"Sport\"],
  \"description\": [\"Fastfood-Restaurant\", \"Döner-Restaurant\", \"Pizzeria\", \"Städtische Bücherei Ahlen\", \"Kino CinemAhlen, weitere Informationen und Programm unter cinemahlen.de\", \"Edeka-Markt\", \"Edeka-Markt\", \"Rossmann Drogeriemarkt\", \"Rossmann Drogeriemarkt\", \"Wochenmarkt\", \"dm-Drogeriemarkt\", \"Eletronik-Fachhandel\", \"Sportpark Nord\", \"Freibad Ahlen\", \"Parkbad Ahlen\"]
}";

$json = json_decode($json, true);

$i = 0;

$host = '127.0.0.1';
$db   = 'meinahlen';
$user = 'root';
$pass = '';
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

print_r($json);


foreach ($json["xCoord"] as $p) {
    $stmt = $pdo->prepare("INSERT INTO `locations` (`id`, `name`, `category`, `description`, `xCoord`, `yCoord`) VALUES (NULL, ?, ?, ?, ?, ?)");
    $stmt->execute(array($json["title"][$i], $json["sDesc"][$i], $json["description"][$i], $json["xCoord"][$i], $json["yCoord"][$i]));
    $i++;
}