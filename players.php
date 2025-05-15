<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}

include("php/connection.php");

if (isset($_POST["type"])) {

    $type = $_POST['type'];

    // Create new player
    if ($type == 'create') {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $team = $_POST['team'] ?? null;
        $team = ($team === "") ? null : $team;
        $photo = 'placeholder.png';

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
            $fileInfo = pathinfo($_FILES['photo']['name']);
            $fileExtension = strtolower($fileInfo['extension'] ?? '');
            $randomInt = mt_rand(10000000, 99999999);

            $photo = $username . '_' . $randomInt . '.' . $fileExtension;
            $path = __DIR__ . '/img/uploads/players/' . $photo;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $path)) {
                $imagick = new Imagick($path);
                $imagick->resizeImage(720, 1080, Imagick::FILTER_LANCZOS, 1);
                $imagick->writeImage($path);
                $imagick->clear();
                $imagick->destroy();
            }
        } else {
            $randomInt = mt_rand(10000000, 99999999);
            copy(
                "img/uploads/players/placeholder.png",
                "img/uploads/players/" . $username . "_" . $randomInt . ".png"
            );
            $photo = $username . '_' . $randomInt . '.png';
        }

        $query = "INSERT INTO players (username, fullname, photo, team_id) VALUES (:username, :fullname, :photo, :team)";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':username' => $username, ':fullname' => $fullname, ':team' => $team, ':photo' => $photo]);
        $attempt = $stmt->fetch();
    } 
    // Modify player
    else if ($type == 'modify') {
        $id = $_POST['id'];
        $currentphoto = $_POST['currentphoto'];
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $team = $_POST['team'] ?? null;
        $team = ($team === "") ? null : $team;

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
            unlink('img/uploads/players/' . $currentphoto);

            $fileInfo = pathinfo($_FILES['photo']['name']);
            $fileExtension = strtolower($fileInfo['extension'] ?? '');
            $randomInt = mt_rand(10000000, 99999999);

            $photo = $username . '_' . $randomInt . '.' . $fileExtension;
            $path = __DIR__ . '/img/uploads/players/' . $photo;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $path)) {
                $imagick = new Imagick($path);
                $imagick->resizeImage(720, 1080, Imagick::FILTER_LANCZOS, 1);
                $imagick->writeImage($path);
                $imagick->clear();
                $imagick->destroy();
            }
        } else {
            $photo = $currentphoto;
        }

        $query = "UPDATE players SET username = :username, fullname = :fullname, photo = :photo, team_id = :team WHERE player_id = :id";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':username' => $username, ':fullname' => $fullname, ':team' => $team, ':photo' => $photo, ':id' => $id]);
        $attempt = $stmt->fetch();
    } 
    // Delete player
    else if ($type == 'delete') {
        $id = $_POST['id'];
        $currentphoto = $_POST['currentphoto'];

        unlink("img/uploads/players/" . $currentphoto);

        $query = "DELETE FROM players WHERE player_id = :id";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $attempt = $stmt->fetch();
    } else {
        echo '<script>alert("Invalid form type submitted.")</script>';
    }
}

$teamQuery = "SELECT * FROM teams";
$teamStmt = $Conn->prepare($teamQuery);
$teamStmt->execute();
$teams = $teamStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Player Management</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php include "php/navbar.php" ?>

    <div class="col-md-10">
        <div class="container py-5">
            <div class="col text-center text-dark">
                <h1>Player Management</h1>
                <hr class="bg-light border-2 border-top border-dark">
                <button type="button" class="btn btn-primary center" data-bs-toggle="modal" data-bs-target="#create">
                    Create Player
                </button>
            </div>

            <div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createLabel">Create Player</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="type" value="create">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="fullname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Player Picture [Optional]</label>
                                    <input class="form-control" type="file" id="photo" name="photo" accept=".png"
                                        aria-describedby="phototip">
                                    <div id="phototip" class="form-text">
                                        Photo will be stretched to a vertical 2:3 aspect ratio. Background can be removed
                                        using <a href="https://www.remove.bg/">remove.bg</a>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="team" class="form-label">Team [Optional]</label>
                                    <select id='team' name='team' class='form-select'>
                                        <?php
                                        foreach ($teams as $teamRow) {
                                            echo "<option value='{$teamRow['team_id']}'>{$teamRow['teamname']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" value="Submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="bg-light border-2 border-top border-dark">

            <div class="row row-cols-md-4 g-4">
                <?php
                include("php/connection.php");

                $query = "SELECT players.*, teams.teamname FROM players JOIN teams ON players.team_id = teams.team_id;";
                $stmt = $Conn->prepare($query);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['player_id'];
                    $username = $row['username'];
                    $fullname = $row['fullname'];
                    $photo = $row['photo'];
                    $team = $row['team_id'];
                    $teamname = $row['teamname'];

                    echo "
                    <div class='col d-flex justify-content-center'>
                        <div class='card border text-bg-dark border-primary '>
                            <img class='card-img-top' src='https://broadcast.travisbrown.co.uk/img/uploads/players/$photo'>
                            <div class='card-img-overlay d-flex flex-column justify-content-end text-center'>
                                <h5 class='card-title username'>$username</h5>
                                <p class='card-text full-name'>$fullname - $teamname</p>
                                <div class='btn-group'>
                                    <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modify$id'>Modify</button>
                                    <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#delete$id'>Delete</button>
                                </div>
                            </div>
                        </div>

                        <div class='modal fade' id='modify$id' tabindex='-1' aria-labelledby='modifylabel$id' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='modifylabel$id'>Modify $username</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <form method='POST' enctype='multipart/form-data'>
                                            <input type='hidden' name='type' value='modify'>
                                            <input type='hidden' name='id' value='$id'>
                                            <input type='hidden' name='currentphoto' value='$photo'>
                                            <div class='mb-3'>
                                                <label for='username' class='form-label'>Username</label>
                                                <input type='text' class='form-control' name='username' value='$username' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label for='fullname' class='form-label'>Full Name</label>
                                                <input type='text' class='form-control' name='fullname' value='$fullname' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label for='photo' class='form-label'>Player Picture [Optional]</label>
                                                <input class='form-control' type='file' id='photo' name='photo' accept='.png' aria-describedby='phototip'>
                                                <div id='phototip' class='form-text'>
                                                    Photo will be stretched to a vertical 2:3 aspect ratio. Background can be removed using <a href='https://www.remove.bg/'>remove.bg</a>
                                                </div>
                                            </div>
                                            <div class='mb-3'>
                                                <label for='team' class='form-label'>Team</label>
                                                <select id='team' name='team' class='form-select'>
                                                    <option value='$team'>No team change</option>";
                                                    foreach ($teams as $teamRow) {
                                                        echo "<option value='{$teamRow['team_id']}'>{$teamRow['teamname']}</option>";
                                                    }
                                                    echo "
                                                </select>
                                            </div>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='submit' class='btn btn-primary' value='Submit'>Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='modal fade' id='delete$id' tabindex='-1' aria-labelledby='deletelabel$id' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='deletelabel$id'>Are you sure you wish to delete $username?</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <p>This action cannot be undone!</p>
                                        <form method='POST' enctype='multipart/form-data'>
                                            <input type='hidden' name='type' value='delete'>
                                            <input type='hidden' name='id' value='$id'>
                                            <input type='hidden' name='currentphoto' value='$photo'>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='submit' class='btn btn-primary' value='Submit'>Delete $username</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
                }
                ?>

            </div>
        </div>

        <script src="js/manager.js"></script>
</body>

</html>