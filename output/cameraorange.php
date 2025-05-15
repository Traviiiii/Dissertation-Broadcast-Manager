<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../php/connection.php");
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

    <div class="container-fluid h-100 background-img">
        <div class="row align-items-end h-100">
            <?php
            $query = "SELECT * FROM cameras WHERE team = 'orange'";
            $stmt = $Conn->prepare($query);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $username = $row['username'];
                $link = $row['link'];
                $id = $row['camera_id'];

                echo "
            <div class='col bg-primary p-0 m-0 camera-row' style='height: 25vh;'>
                <iframe class='team-camera' src='https://vdo.ninja/?view=$link&noaudio&fit' style='height: 100%; width: 100%;'></iframe>
            </div>
                ";
            }
            ?>
        </div>
    </div>


</body>

</html>