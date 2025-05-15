<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../php/connection.php");

$query = "SELECT m.*, ta.teamname AS teama_name, ta.logo AS teama_logo, tb.teamname AS teamb_name, tb.logo AS teamb_logo FROM matches m JOIN teams ta ON m.teama_id = ta.team_id JOIN teams tb ON m.teamb_id = tb.team_id WHERE m.active = 1 ORDER BY m.active DESC;
";
$stmt = $Conn->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['match_id'];
    $teama = $row['teama_name'];
    $teama_logo = $row['teama_logo'];
    $teamb_logo = $row['teamb_logo'];
    $veto = $row['veto'];
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
        <div class="row h-75 bg-dark veto-row" style="width: 90vw;">
            <?php
            $vetoes = json_decode($veto, true);
            foreach ($vetoes as $item) {
                $map = htmlspecialchars($item['map']);
                $team = $item['team'];
                $action = $item['action'];
                $greyscale = ($action === 'ban') ? 'filter: grayscale(100%);' : '';
                $logo = 'placeholder.png';

                if ($team === 'teama') {
                    $logo = $teama_logo;
                } elseif ($team === 'teamb') {
                    $logo = $teamb_logo;
                }

                echo "<div class='col d-flex flex-column align-items-center justify-content-center map-stage' style='background-image: url(\"https://r6-game-data-api.onrender.com/uploads/$map\"); $greyscale'>";
                if ($action !== 'decider') {
                    echo "<img src='https://broadcast.travisbrown.co.uk/img/uploads/teams/$logo' class='img-fluid p-3'>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelector('.veto-row').classList.add('loaded');
            }, 1000);
        });
    </script>
</body>


</html>