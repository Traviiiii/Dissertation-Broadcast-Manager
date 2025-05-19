<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Update seetings
if (isset($_POST["type"])) {
    include("php/connection.php");
    $type = $_POST['type'];

    if ($type == 'settings') {
        $sessionpassword = $_POST['password'];
        $theme = $_POST['theme'];
        $title = $_POST['title'];

        $query = "UPDATE settings SET sessionpassword = :sessionpassword, theme = :theme, title = :title";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':sessionpassword' => $sessionpassword, ':theme' => $theme, ':title' => $title]);
    } else {
        echo '<script>alert("Invalid form type submitted.")</script>';
    }
}

// Create new admin user
if (isset($_POST["create"])) {
    include("php/connection.php");

    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "INSERT INTO users (email, password) VALUES (:email, :password)";

    $stmt = $Conn->prepare($query);
    $stmt->execute([':email' => $email, ':password' => $password]);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Settings</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php include "php/navbar.php" ?>

    <div class="col-md-10">
        <div class="container py-5">

            <?php
            include("php/connection.php");

            $query = "SELECT * FROM settings";
            $stmt = $Conn->prepare($query);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $password = $row['sessionpassword'];
                $theme = $row['theme'];
                $title = $row['title'];
            } ?>

            <form method="POST" enctype="multipart/form-data">
                <h2>Change Stream Settings</h2>
                <input type="hidden" name="type" value="settings">
                <div class="mb-3">
                    <label for="password" class="form-label">Session Password</label>
                    <input type="password" class="form-control" name="password" value="<?php echo $password ?>" required>
                </div>
                <div class="mb-3">
                    <label for="theme" class="form-label">Theme</label>
                    <select id="theme" name="theme" class="form-select">
                        <option value="<?php echo $theme ?>">No Change (<?php echo $theme ?>)</option>z
                        <option value="ssi">Showdown Series Invitational</option>
                        <option value="apl">BLAST R6: Asia Pacific League</option>
                        <option value="siegex">Siege X</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Stream Title</label>
                    <input type="text" class="form-control" name="title" value="<?php echo $title ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" value="Submit">Save</button>
            </form>


            <hr>

            <form method='POST'>
                <h2>Create Admin Account</h2>
                <input type="hidden" name="create">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type='submit' class='btn btn-primary' value='Submit'>Create Account</button>
            </form>
        </div>
    </div>

    <script src="js/manager.js"></script>
</body>

</html>