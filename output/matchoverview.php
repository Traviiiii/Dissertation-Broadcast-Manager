<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../php/connection.php");

$query = "SELECT m.*, ta.teamname AS teama_name, ta.logo AS teama_logo, tb.teamname AS teamb_name, tb.logo AS teamb_logo FROM matches m JOIN teams ta ON m.teama_id = ta.team_id JOIN teams tb ON m.teamb_id = tb.team_id WHERE m.active = 1 ORDER BY m.active DESC;
";
$stmt = $Conn->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $teama = $row['teama_name'];
    $teama_logo = $row['teama_logo'];
    $teamb = $row['teamb_name'];
    $teamb_logo = $row['teamb_logo'];
    $score = $row['teama_score'] . " - " . $row['teamb_score'];
    $title = $row['title'];
    $bestof = $row['bestof'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Output Page</title>
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/manager.css">

    <?php

    $query = "SELECT theme FROM settings LIMIT 1";
    $stmt = $Conn->prepare($query);
    $stmt->execute();
    $currentTheme = $stmt->fetchColumn();

    echo "<link rel='stylesheet' href='../themes/$currentTheme.css'>";


    ?>
</head>

<body>

    <div class="container-fluid h-100 background-img d-flex justify-content-center align-items-center">
        <div class="row w-75 h-50 bg-dark">
            <div class="col d-flex flex-column align-items-center justify-content-center p-5">
                <h1><?= $teama ?></h1>
                <img src="https://broadcast.travisbrown.co.uk/img/uploads/teams/<?= $teama_logo ?>" class="img-fluid p-5">
            </div>
            <div class="col d-flex align-items-center justify-content-center text-center">
                <h2>
                    <span class="highlight"> <?= $title ?> </span>
                    <hr>
                    <?= $score ?>
                    <br>
                    <span class="fs-5">Best of <?= $bestof ?></span>
            </h2>
            </div>
            <div class="col d-flex flex-column align-items-center justify-content-center p-5">
                <h1><?= $teamb ?></h1>
                <img src="https://broadcast.travisbrown.co.uk/img/uploads/teams/<?= $teamb_logo ?>" class="img-fluid p-5">
            </div>
        </div>
    </div>

</body>

</html>