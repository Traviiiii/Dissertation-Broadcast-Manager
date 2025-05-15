<?php
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

    $query = "SELECT title FROM settings LIMIT 1";
    $stmt = $Conn->prepare($query);
    $stmt->execute();
    $streamTitle = $stmt->fetchColumn();
    ?>
</head>

<body class="background-img text-center vh-100 d-flex justify-content-center align-items-center">
    <div>
        <h1 class="highlight display-1">Stream is Ending.</h1>
        <p class="h2">Thank you for watching!</p>
    </div>
</body>

</html>