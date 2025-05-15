<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}

// Retrieve saved schedule
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include("php/connection.php");

    $query = "SELECT schedule FROM settings";
    $stmt = $Conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $jsonResult = json_encode($result);
}

// Save new schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("php/connection.php");

    $json = file_get_contents('php://input');
    error_log("Received JSON: " . $json);

    $data = json_decode($json, true);
    $eventData = json_encode($data['eventData']);

    $query = "UPDATE settings SET schedule = :eventData";

    $stmt = $Conn->prepare($query);
    $stmt->execute([':eventData' => $eventData]);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Schedule Management</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php

    include "php/navbar.php";
    include("php/connection.php");

    $teamQuery = "SELECT * FROM teams";
    $teamStmt = $Conn->prepare($teamQuery);
    $teamStmt->execute();
    $teams = $teamStmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="col-md-10">
        <div class="container py-5">

            <div class="col text-center text-dark">
                <h1>Schedule Management</h1>

                <hr class="bg-light border-2 border-top border-dark">
                <form id="vetoForm" method="POST" enctype="multipart/form-data">
                    <div class="card p-2">
                        <div class="d-flex gap-2">
                            <div class="form-control border-0">Title</div>
                            <div class="form-control border-0">Time</div>
                            <div class="form-control border-0">Next</div>
                            <div class="form-control border-0">Remove</div>
                        </div>
                    </div>
                    <div id="scheduleContainer"></div>
                    <br>
                    <button type="button" onclick="addEvent()" class="btn btn-primary m-1">Add Event</button>
                    <button type="button" onclick="saveSchedule()" class="btn btn-success m-1">Save</button>
                </form>

            </div>
        </div>


        <script src="js/manager.js"></script>
        <script src="js/schedule.js"></script>

        <script>
            function loadSchedule() {
                const scheduleData = <?php echo isset($jsonResult) ? $jsonResult : 'null'; ?>;

                if (scheduleData && scheduleData.schedule) {
                    const scheduleStages = JSON.parse(scheduleData.schedule);

                    scheduleStages.forEach(event => {
                        addEvent(event.title, event.time, event.next);
                    });
                }
            }

            loadSchedule();
        </script>
</body>

</html>