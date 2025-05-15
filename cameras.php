<?php
// Check if the User is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}

// Update camera to whichever team is selected
if (isset($_POST["id"])) {
    include("php/connection.php");

    $id = $_POST['id'];
    $team = $_POST['team'];

    if ($team == 'none') {
        $team = null;
    }

    $query = "UPDATE cameras SET team = :team WHERE camera_id = :id";

    $stmt = $Conn->prepare($query);
    $stmt->execute([':id' => $id, ':team' => $team]);
    $attempt = $stmt->fetch();

    header('Location: cameras');
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Camera Management</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php include "php/navbar.php" ?>
    <div class="col-md-10">
        <div class="container py-5 text-center">
            <h1>Camera Management</h1>
            <p>If no cameras are showing below, there are none currently active. Stored cameras are reset automatically bi-weekly.</p>
<!-- Dynamically place cameras based on which teams are assigned -->
            <hr>
            <div class="row">
                <div class="col">
                    <h5>Unassigned Cameras</h5>
                    <?php
                    include("php/connection.php");
                    $query = "SELECT * FROM cameras WHERE team is null";
                    $stmt = $Conn->prepare($query);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $username = $row['username'];
                            $link = $row['link'];
                            $id = $row['camera_id'];

                            echo "
                            <div class='bg-dark text-white p-2 w-25 rounded mx-auto'>
                                <h5><a href='https://vdo.ninja/?view=$link' style='text-decoration: none; color: inherit;'>$username</a></h5>
                                <div class='d-flex justify-content-center'>
                                    <form method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='hidden' name='team' value='blue'>
                                        <button type='submit' class='btn btn-info m-1'>Blue</button>
                                    </form>
                                    <form method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='hidden' name='team' value='orange'>
                                        <button type='submit' class='btn btn-warning m-1'>Orange</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            ";
                        }
                    } else {
                        echo "<p class='text-muted'>No cameras found.</p>";
                    }
                    ?>
                </div>
                <hr>
                <div class="col-md-6">
                    <h5>Blue Team</h5>
                    <?php
                    include("php/connection.php");
                    $query = "SELECT * FROM cameras WHERE team = 'blue'";
                    $stmt = $Conn->prepare($query);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $username = $row['username'];
                            $link = $row['link'];
                            $id = $row['camera_id'];

                            echo "
                            <div class='bg-dark text-dark p-2 w-50 rounded mx-auto'>
                                <h5 class='text-white'>$username</h5>
                                <div class='d-flex justify-content-center'>
                                    <form method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='hidden' name='team' value='orange'>
                                        <button type='submit' class='btn btn-warning m-1'>Orange</button>
                                    </form>
                                    <form method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='hidden' name='team' value='none'>
                                        <button type='submit' class='btn btn-danger m-1'>Unassign</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            ";
                        }
                    } else {
                        echo "<p class='text-muted'>No cameras found.</p>";
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <h5>Orange Team</h5>
                    <?php
                    include("php/connection.php");
                    $query = "SELECT * FROM cameras WHERE team = 'orange'";
                    $stmt = $Conn->prepare($query);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $username = $row['username'];
                            $link = $row['link'];
                            $id = $row['camera_id'];

                            echo "
                            <div class='bg-dark text-dark p-2 w-50 rounded mx-auto'>
                                <h5 class='text-white'>$username</h5>
                                <div class='d-flex justify-content-center'>
                                    <form method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='hidden' name='team' value='blue'>
                                        <button type='submit' class='btn btn-info m-1'>Blue</button>
                                    </form>
                                    <form method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='hidden' name='team' value='none'>
                                        <button type='submit' class='btn btn-danger m-1'>Unassign</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            ";
                        }
                    } else {
                        echo "<p class='text-muted'>No cameras found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <script src="js/manager.js"></script>
</body>

</html>