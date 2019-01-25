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
    if(!isset($_GET["id"]) || empty($_GET["id"]) || !is_numeric($_GET["id"])) {
        die("<h1 class='centered page_text'>Kein Ort ausgewählt!</h1><br /><a class='page_text centered' href='index.php'><p class='page_text centered'>Zurück</p></a>");
    }
?>
</body>
</html>