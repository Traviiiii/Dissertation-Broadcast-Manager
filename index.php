<?php
if (isset($_POST["email"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include("php/connection.php");

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $Conn->prepare($query);
    $stmt->execute([':email' => $email]);
    $attempt = $stmt->fetch();

    if ($attempt && password_verify($password, $attempt['password'])) {
        session_start();
        $_SESSION['logged_in'] = true;
        header("Location: players");
        exit;
    } else {
        echo "Sign-in failed";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Login</title>
    <?php include "php/header.php" ?>
</head>

<body class="d-flex justify-content-center align-items-center login-body">

    <div class="container col-md-4 bg-white border border-5 border-primary rounded p-5 text-center ">
        <h1>Login Page</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" value="Submit">Submit</button>
        </form>
    </div>

    <script src="js/manager.js"></script>
</body>

</html>