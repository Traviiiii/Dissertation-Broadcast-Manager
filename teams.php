<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}

if (isset($_POST["type"])) {
    include("php/connection.php");
    $type = $_POST['type'];

    // Create new player
    if ($type == 'create') {
        $teamname = $_POST['name'];
        $photo = 'placeholder.png';

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
            $fileInfo = pathinfo($_FILES['photo']['name']);
            $fileExtension = strtolower($fileInfo['extension'] ?? '');
            $randomInt = mt_rand(10000000, 99999999);

            $photo = $teamname . '_' . $randomInt . '.' . $fileExtension;
            $path = __DIR__ . '/img/uploads/teams/' . $photo;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $path)) {
                $imagick = new Imagick($path);
                $imagick->resizeImage(512, 512, Imagick::FILTER_LANCZOS, 1);
                $imagick->writeImage($path);
                $imagick->clear();
                $imagick->destroy();
            }
        } else {
            $randomInt = mt_rand(10000000, 99999999);
            copy(
                "img/uploads/teams/placeholder.png",
                "img/uploads/teams/" . $teamname . "_" . $randomInt . ".png"
            );
            $photo = $teamname . '_' . $randomInt . '.png';
        }

        $query = "INSERT INTO teams (teamname, logo) VALUES (:teamname, :photo)";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':teamname' => $teamname, ':photo' => $photo]);
        $attempt = $stmt->fetch();
    } 
    // Modify pre-existing player
    else if ($type == 'modify') {
        $id = $_POST['id'];
        $currentphoto = $_POST['currentphoto'];
        $teamname = $_POST['name'];
        $photo = 'placeholder.png';

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
            unlink('img/uploads/teams/' . $currentphoto);

            $fileInfo = pathinfo($_FILES['photo']['name']);
            $fileExtension = strtolower($fileInfo['extension'] ?? '');
            $randomInt = mt_rand(10000000, 99999999);

            $photo = $teamname . '_' . $randomInt . '.' . $fileExtension;
            $path = __DIR__ . '/img/uploads/teams/' . $photo;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $path)) {
                $imagick = new Imagick($path);
                $imagick->resizeImage(512, 512, Imagick::FILTER_LANCZOS, 1);
                $imagick->writeImage($path);
                $imagick->clear();
                $imagick->destroy();
            }
        } else {
            $photo = $currentphoto;
        }

        $query = "UPDATE teams SET teamname = :teamname, logo = :logo WHERE team_id = :id";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':teamname' => $teamname, ':logo' => $photo, ':id' => $id]);
        $attempt = $stmt->fetch();
        } 
        // Delete player
        else if ($type == 'delete') {
            $id = $_POST['id'];
            $currentphoto = $_POST['currentphoto'];

            unlink("img/uploads/teams/" . $currentphoto);

            $queries = [
                "DELETE FROM matches WHERE teama_id = :id",
                "DELETE FROM matches WHERE teamb_id = :id",
                "DELETE FROM players WHERE team_id = :id",
                "DELETE FROM teams WHERE team_id = :id"
            ];

            foreach ($queries as $query) {
                $stmt = $Conn->prepare($query);
                $stmt->execute([':id' => $id]);
            }
        } else {
        echo '<script>alert("Invalid form type submitted.")</script>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Team Management</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php include "php/navbar.php" ?>

    <div class="col-md-10">
        <div class="container py-5">
            <div class="col text-center text-dark">
                <h1>Team Management</h1>
                <hr class="bg-light border-2 border-top border-dark">
                <button type="button" class="btn btn-primary center" data-bs-toggle="modal" data-bs-target="#create">
                    Create Team
                </button>
            </div>

            <div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createLabel">Create Team</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="type" value="create">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Team Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Team Picture [Optional]</label>
                                    <input class="form-control" type="file" id="photo" name="photo" accept=".png" aria-describedby="phototip">
                                    <div id="phototip" class="form-text">
                                        Photo will be stretched to a square 1:1 aspect ratio. Background can be removed using <a href="https://www.remove.bg/">remove.bg</a>
                                    </div>
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

            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php
                include("php/connection.php");

                $query = "SELECT * FROM teams";
                $stmt = $Conn->prepare($query);
                $stmt->execute();

                // Show all teams, generate modal underneath each

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['team_id'];
                    $teamname = $row['teamname'];
                    $photo = $row['logo'];

                    $playerQuery = "SELECT * FROM players WHERE team_id = :team_id ";
                    $playerStmt = $Conn->prepare($playerQuery);
                    $playerStmt->execute([':team_id' => $id]);
                    $players = $playerStmt->fetchAll(PDO::FETCH_ASSOC);

                    echo "
            <div class='col d-flex justify-content-center'>
                <div class='card border text-bg-dark border-primary '>
                    <img class='card-img' src='https://broadcast.travisbrown.co.uk/img/uploads/teams/$photo'>
                    <div class='d-flex flex-column justify-content-end text-center'>
                        <h5 class='card-title username'>$teamname</h5>
                        <p class='card-text full-name'> ‎ ";
                    foreach ($players as $playerRow) {
                        echo $playerRow['username'] . ", ";
                    }
                    echo "
                        </p>
                        <div class='btn-group'>
                            <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modify$id'>Modify</button>
                            <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#delete$id'>Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class='modal fade' id='modify$id' tabindex='-1' aria-labelledby='modifylabel$id' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='modifylabel$id'>Modify $teamname</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <form method='POST' enctype='multipart/form-data'>
                                <input type='hidden' name='type' value='modify'>
                                <input type='hidden' name='id' value='$id'>
                                <input type='hidden' name='currentphoto' value='$photo'>
                                <div class='mb-3'>
                                    <label for='name' class='form-label'>Team Name</label>
                                    <input type='text' class='form-control' name='name' value='$teamname' required>
                                </div>
                                <div class='mb-3'>
                                    <label for='photo' class='form-label'>Team Picture [Optional]</label>
                                    <input class='form-control' type='file' id='photo' name='photo' accept='.png' aria-describedby='phototip'>
                                    <div id='phototip' class='form-text'>
                                        Photo will be stretched to a square 1:1 aspect ratio. Background can be removed using <a href='https://www.remove.bg/'>remove.bg</a>
                                    </div>
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
                            <h5 class='modal-title' id='deletelabel$id'>Are you sure you wish to delete $teamname?</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <p>This will also delete any players/matches tied to this team!</p>
                            <form method='POST' enctype='multipart/form-data'>
                                <input type='hidden' name='type' value='delete'>
                                <input type='hidden' name='id' value='$id'>
                                <input type='hidden' name='currentphoto' value='$photo'>
                        </div>
                        <div class='modal-footer'>
                            <button type='submit' class='btn btn-primary' value='Submit'>Delete $teamname</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        ";
                }
                ?>
            </div>
        </div>

    
        <script src="js/manager.js"></script>
</body>

</html>