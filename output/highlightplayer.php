<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../php/connection.php");

$query = "SELECT player FROM settings LIMIT 1";
$stmt = $Conn->prepare($query);
$stmt->execute();
$currentPlayer = $stmt->fetchColumn();

$query = "SELECT * FROM players WHERE player_id = :player_id";
$stmt = $Conn->prepare($query);
$stmt->execute([':player_id' => $currentPlayer]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $photo = $row['photo'];
    $username = $row['username'];
    $fullname = $row['fullname'];
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

    <div class="container highlight-container player-container">
        <img class="player-img" src="https://broadcast.travisbrown.co.uk/img/uploads/players/<?= htmlspecialchars($photo) ?>">
        <div class="player-text">
            <h1 class="highlight"><?= htmlspecialchars($username) ?></h1>
            <h2><?= htmlspecialchars($fullname) ?></h2>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelector('.container').classList.add('loaded');
            }, 1000);
        });
    </script>


</body>

</html>