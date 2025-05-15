<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../php/connection.php");

$query = "SELECT schedule FROM settings";
$stmt = $Conn->prepare($query);
$stmt->execute();

$schedule = '';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $schedule = $row['schedule'];
}
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

<body>

    <div class="position-absolute top-0 start-0 p-5 w-25">
        <div class="background-img text-white text-start p-2 schedule-top">
            <h3 class="highlight m-0 h3"><?php echo $streamTitle ?></h5>
            <h5 class="m-0 h5">Returning shortly</h5>
        </div>
        <div class="bg-dark bg-opacity-75 p-3">
            <?php
            $events = json_decode($schedule, true);

            foreach ($events as $event) {
                $title = htmlspecialchars($event["title"]);
                $time = htmlspecialchars($event["time"]);
                $isNext = isset($event["next"]) && $event["next"] === "on";

                echo '
                <div class="d-flex justify-content-between text-white py-2' . ($isNext ? ' schedule-next ' : '') . '">
                    <span>' . $title . '</span>
                    <span>' . $time . '</span>
                </div>
            ';
            }
            ?>

        </div>
    </div>
</body>

</html>