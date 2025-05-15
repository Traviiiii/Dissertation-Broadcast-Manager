<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}

include("php/connection.php");

// Fetch current highlight
$query = "SELECT player FROM settings LIMIT 1";
$stmt = $Conn->prepare($query);
$stmt->execute();
$currentPlayer = $stmt->fetchColumn();

// Update highlight
if (isset($_POST["type"])) {
    $type = $_POST['type'];

    if ($type == 'player') {
        $player = $_POST['player'];

        $query = "UPDATE settings SET player = :player";
        $stmt = $Conn->prepare($query);
        $stmt->execute([':player' => $player]);

        $currentPlayer = $player;
    } else {
        echo '<script>alert("Invalid form type submitted.")</script>';
    }
}

// Select all players
$query = "SELECT * FROM players WHERE player_id = :player_id";
$stmt = $Conn->prepare($query);
$stmt->execute([':player_id' => $currentPlayer]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $currentPhoto = $row['photo'];
    $currentUsername = $row['username'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Highlight Player</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php include "php/navbar.php" ?>

    <div class="col-md-10">
        <div class="container py-5">

            <h1 class="text-center">Player Highlight</h1>
            <hr class="bg-light border-2 border-top border-dark">

            <form method='POST' enctype='multipart/form-data'>
                <input type='hidden' name='type' value='player'>
                <div class='mb-3'>
                    <label for='player' class='form-label'>Player Highlight Option</label>
                    <select id='player' name='player' class='form-select'>
                        <?php
                        include("php/connection.php");

                        $query = "SELECT * FROM players";
                        $stmt = $Conn->prepare($query);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $id = $row['player_id'];
                            $username = $row['username'];
                            echo "
                                    <option value='$id'>$username</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type='submit' class='btn btn-primary'>Update</button>
            </form>

            <hr class="bg-light border-2 border-top border-dark">

            <h2 id="currentSelection" class="text-center">Current Selection: <span id="playerName"><?= htmlspecialchars($currentUsername) ?></span></h2>

            <div class="row">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <img class="img-fluid" src="https://broadcast.travisbrown.co.uk/img/uploads/players/<?= htmlspecialchars($currentPhoto) ?>">
                </div>
                <div class="col-md-4">
                </div>
            </div>

        </div>
    </div>

    <script src="js/manager.js"></script>
</body>

</html>