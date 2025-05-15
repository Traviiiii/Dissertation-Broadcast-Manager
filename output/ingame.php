<?php
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
    $title = $row['title'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Output Page</title>
    <link rel="stylesheet" href="../css/output.css">
    <?php

    $query = "SELECT theme FROM settings LIMIT 1";
    $stmt = $Conn->prepare($query);
    $stmt->execute();
    $currentTheme = $stmt->fetchColumn();

    echo "<link rel='stylesheet' href='../themes/$currentTheme.css'>";
    ?>
</head>

<body>
    <div class="game-banner">
        <div class="ingame-left">
            <?php             
                echo "<h2>$title</h2>";
            ?>
        </div>
        <div class="ingame-center">
            <img src='https://broadcast.travisbrown.co.uk/img/uploads/teams/<?php echo $teama_logo ?>' class='ingame-logo'>
            <div class="ingame-tournament-logo"></div>
            <img src='https://broadcast.travisbrown.co.uk/img/uploads/teams/<?php echo $teamb_logo ?>' class='ingame-logo'>
        </div>
        <div class="ingame-right">
            <?php
                $vetoes = json_decode($veto, true);
                foreach ($vetoes as $item) {
                    $map = htmlspecialchars($item['map']);
                    $action = $item['action'];
                    $status = $item['status'];

                    if ($action == 'decider' || $action == 'pick') {
                        echo "
                        <div class='ingame-map' style='background-image: url(\"https://r6-game-data-api.onrender.com/uploads/$map\");'>
                        <span>$status</span>
                        </div>
                        ";
                    }
                }
            ?>
        </div>
    </div>
</body>


</html>