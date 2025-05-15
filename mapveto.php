<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}

// Load veto
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include("php/connection.php");

    $id = $_GET['id'];
    $query = "SELECT veto FROM matches WHERE match_id = :id";
    $stmt = $Conn->prepare($query);
    $stmt->execute([':id' => $id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $jsonResult = json_encode($result);
}

// Save veto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("php/connection.php");

    if (isset($_POST["type"])) {

        $id = $_GET['id'];
        $title = $_POST['title'];
        $ascore = $_POST['scorea'];
        $bscore = $_POST['scoreb'];

        $query = "UPDATE matches SET teama_score = :ascore, teamb_score = :bscore, title = :title WHERE match_id = :id";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':title' => $title, ':ascore' => $ascore, ':bscore' => $bscore, ':id' => $id]);
    } else {

        $json = file_get_contents('php://input');
        error_log("Received JSON: " . $json);

        $id = $_GET['id'];
        $data = json_decode($json, true);
        $vetoData = json_encode($data['vetoData']);

        $query = "UPDATE matches SET veto = :vetoData WHERE match_id = :id";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':vetoData' => $vetoData, ':id' => $id]);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Veto Management</title>
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
                <h1>Veto Management</h1>
                <?php
                // Error msg if no ID is found
                if (!isset($_GET['id']) || empty($_GET['id'])) {
                    echo "
                        <div class='alert alert-danger' role='alert'>
                            No match ID has been recieved, therefore this page cannot be used. 
                            <br>
                            Please return to the <a href='/matches' class='alert-link'>matches page</a> to continue.
                        </div>
                    ";
                    exit;
                }

                include("php/connection.php");

                $id = $_GET['id'];

                $query = "SELECT m.match_id, m.title, ta.teamname AS teama_name, tb.teamname AS teamb_name, m.teama_score, m.teamb_score FROM matches m JOIN teams ta ON m.teama_id = ta.team_id JOIN teams tb ON m.teamb_id = tb.team_id WHERE m.match_id = :id";
                $stmt = $Conn->prepare($query);
                $stmt->execute([':id' => $id]);

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $teama = $row['teama_name'];
                $teamb = $row['teamb_name'];
                $ascore = $row['teama_score'];
                $bscore = $row['teamb_score'];
                $score = $row['teama_score'] . " - " . $row['teamb_score'];
                $title = $row['title'];

                echo "<h5>$teama [$score] $teamb</h5>";
                ?>
                <h5><a href="/matches">Return to matches page</a></h5>

                    <form method="POST" enctype="multipart/form-data">
                        <h2>Change Stream Settings</h2>
                        <input type="hidden" name="type" value="settings">
                        <div class="row align-items-end">
                            <div class="col">
                                <label for="title" class="form-label">Match Title</label>
                                <input type="text" class="form-control" name="title" value="<?php echo $title ?>" required>
                            </div>
                            <div class="col">
                                <label for="scorea" class="form-label"><?php echo $teama ?> Score</label>
                                <input type="number" class="form-control" name="scorea" value="<?php echo $ascore ?>" required>
                            </div>
                            <div class="col">
                                <label for="scoreb" class="form-label"><?php echo $teamb ?> Score</label>
                                <input type="number" class="form-control" name="scoreb" value="<?php echo $bscore ?>" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>


                    <hr class="bg-light border-2 border-top border-dark">
                    <form autocomplete='off' id="vetoForm" method="POST" enctype="multipart/form-data">
                        <div class="card p-2">
                            <div class="d-flex gap-2">
                                <div class="form-control border-0">Map</div>
                                <div class="form-control border-0">Team</div>
                                <div class="form-control border-0">Action</div>
                                <div class="form-control border-0">Status</div>
                            </div>
                        </div>
                        <div id="vetoContainer"></div>
                        <br>
                        <button type="button" onclick="addStage()" class="btn btn-primary m-1">Add Stage</button>
                        <button type="button" onclick="saveVeto()" class="btn btn-success m-1">Save</button>
                    </form>

            </div>
        </div>


        <script src="js/manager.js"></script>
        <script src="js/veto.js"></script>

        <script>
            function loadVeto() {
                const vetoData = <?php echo isset($jsonResult) ? $jsonResult : 'null'; ?>;

                if (vetoData && vetoData.veto) {
                    const vetoStages = JSON.parse(vetoData.veto);

                    vetoStages.forEach(stage => {
                        addStage(stage.map, stage.team, stage.action, stage.status);
                    });
                }
            }

            loadVeto();
        </script>
</body>

</html>