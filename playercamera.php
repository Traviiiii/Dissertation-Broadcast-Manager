<?php
if (isset($_POST["type"])) {
    include("php/connection.php");
    $type = $_POST['type'];

    if ($type == 'create') {
        $password = $_POST['pw'];
        $sql = "SELECT sessionpassword FROM settings";
        $stmt = $Conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $camera = $_POST['camera'];

        // Verifies link length of provided URL then formats it
        if (strlen($camera) == 31) {
            $link = substr($camera, -7);
            // Ensures that the session password is correct
            if ($row["sessionpassword"] == $password) {
                $username = $_POST['username'];
    
                $query = "INSERT INTO cameras (username, link, team) VALUES (:username, :link, null)";
    
                $stmt = $Conn->prepare($query);
                $stmt->execute([':username' => $username, ':link' => $link]);
                $attempt = $stmt->fetch();
    
                // Sends error message relevant to where code stopped
                echo '<script>alert("Camera successfully added.")</script>';
            } else {
                echo '<script>alert("Invalid session password.")</script>';
            }
        } else {
            echo '<script>alert("Invalid link submitted.")</script>';
        }
    } else {
        echo '<script>alert("Invalid form type submitted.")</script>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Camera Management</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <form method="POST" enctype="multipart/form-data">
            <h1>Add Camera</h1>
            <p>Click <a href="https://docs.google.com/document/d/14VEI5H5wh8tpOa2uLSmid1eSfSl1ZGVnTD99KlN0nkI/edit?usp=sharing">here</a> for tutorial on how to use - <a href="https://vdo.ninja/">VDO.Ninja</a></p>
            <input type="hidden" name="type" value="create">
            <div class="mb-3">
                <label for="pw" class="form-label">Session Password</label>
                <input type="password" class="form-control" name="pw" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="camera" class="form-label">Camera Link</label>
                <input type="text" class="form-control" name="camera" required>
            </div>
            <button type="submit" class="btn btn-primary" value="Submit">Submit</button>
        </form>
    </div>

    <script src="js/manager.js"></script>
</body>

</html>