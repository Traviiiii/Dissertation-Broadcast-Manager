<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../php/connection.php");

$query = "SELECT teamb_id FROM matches WHERE active LIMIT 1";
$stmt = $Conn->prepare($query);
$stmt->execute();
$teamb_id = $stmt->fetchColumn();

$query = "SELECT teamname FROM teams WHERE team_id = :teama_id";
$stmt = $Conn->prepare($query);
$stmt->bindParam(':teama_id', $teamb_id);
$stmt->execute();
$teamname = $stmt->fetchColumn();

$query = "SELECT * FROM players WHERE team_id = :teama_id";
$stmt = $Conn->prepare($query);
$stmt->bindParam(':teama_id', $teamb_id);
$stmt->execute();

$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<body class="background-img text-center d-flex flex-column align-items-center justify-content-center">

    <h2 class="display-4 highlight"><?php echo $teamname ?></h2>
    <h2 class="text-dark">Team Overview</h2>
    <div class="container-fluid p-5">
        <div class="row justify-content-center">
            <?php
            foreach ($players as $player) {
                $username = $player['username'];
                $fullname = $player['fullname'];
                $photo = $player['photo'];

                echo "
                <div class='col-6 col-md-4 col-lg-2'>
                    <div class='card border-0'>
                        <img class='card-img-top bg-dark' src='https://broadcast.travisbrown.co.uk/img/uploads/players/$photo'>
                        <div class='card-body bg-dark camera-row'>
                            <h2 class='card-title highlight'>$username</h2>
                            <p class='card-text text-dark'>$fullname</p>
                        </div>
                    </div>
                </div>
                ";
            }
            ?>
        </div>
    </div>

</body>

</html>