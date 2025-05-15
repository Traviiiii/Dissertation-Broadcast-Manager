<?php
/* Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}
*/

if (isset($_POST["type"])) {
    include("php/connection.php");
    $type = $_POST['type'];

    // Create Match
    if ($type == 'create') {
        $teama_id = $_POST['teama'];
        $teamb_id = $_POST['teamb'];
        $title = $_POST['title'];
        $bestof = $_POST['bestof'];

        $query = "INSERT INTO matches (teama_id, teama_score, teamb_id, teamb_score, title, bestof) VALUES (:teama_id, 0, :teamb_id, 0, :title, :bestof)";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':teama_id' => $teama_id, ':teamb_id' => $teamb_id, ':title' => $title, ':bestof' => $bestof]);
    } 
    
    // Delete match
    else if ($type == 'delete') {
        $id = $_POST['id'];

        $query = "DELETE FROM matches WHERE match_id = :id";

        $stmt = $Conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $attempt = $stmt->fetch();
    } 

    //Change active match
    else if ($type == 'setactive') {
        $id = $_POST['id'];
        $reset = $Conn->prepare("UPDATE matches SET active = 0");
        $reset->execute();

        $activate = $Conn->prepare("UPDATE matches SET active = 1 WHERE match_id = :id");
        $activate->execute([':id' => $id]);
    } else {
        echo '<script>alert("Invalid form type submitted.")</script>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Match Management</title>
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
                <h1>Match Management</h1>
                <hr class="bg-light border-2 border-top border-dark">
                <button type="button" class="btn btn-primary center" data-bs-toggle="modal" data-bs-target="#create">
                    Create Match
                </button>
            </div>

            <div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createLabel">Create Match</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="type" value="create">
                                <div class='mb-3'>
                                    <label for='teama' class='form-label'>Team A</label>
                                    <select id='teama' name='teama' class='form-select'>
                                        <?php
                                        foreach ($teams as $teamRow) {
                                            echo "<option value='{$teamRow['team_id']}'>{$teamRow['teamname']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class='mb-3'>
                                    <label for='teamb' class='form-label'>Team B</label>
                                    <select id='teamb' name='teamb' class='form-select'>
                                        <?php
                                        foreach ($teams as $teamRow) {
                                            echo "<option value='{$teamRow['team_id']}'>{$teamRow['teamname']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Match Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Showdown Series Grand Final" required>
                                </div>
                                <div class='mb-3'>
                                    <label for='bestof' class='form-label'>Series Size</label>
                                    <select id='bestof' name='bestof' class='form-select'>
                                        <option value="1">Best of 1</option>
                                        <option value="3">Best of 3</option>
                                        <option value="5">Best of 5</option>
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

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php
                include("php/connection.php");

                $query = "SELECT m.*, ta.teamname AS teama_name, tb.teamname AS teamb_name FROM matches m JOIN teams ta ON m.teama_id = ta.team_id JOIN teams tb ON m.teamb_id = tb.team_id;";
                $stmt = $Conn->prepare($query);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['match_id'];
                    $teama = $row['teama_name'];
                    $teamb = $row['teamb_name'];
                    $score = $row['teama_score'] . " - " . $row['teamb_score'];
                    $title = $row['title'];
                    $active = $row['active'];
                    $bestof = $row['bestof'];

                    $playerQuery = "SELECT * FROM players WHERE team_id = :team_id ";
                    $playerStmt = $Conn->prepare($playerQuery);
                    $playerStmt->execute([':team_id' => $id]);
                    $players = $playerStmt->fetchAll(PDO::FETCH_ASSOC);

                    echo "
                        <div class='col d-flex justify-content-center'>
                            <div class='card border text-bg-dark border-primary w-100'>
                                    <div class='d-flex flex-column justify-content-end text-center'>
                                        <br>
                                        <h5 class='card-title username'>$teama VS $teamb</h5>
                                        <p>
                                            $title
                                            <br>
                                            Best of $bestof | $score
                                        </p>
                                        <div class='btn-group'>
                                        <a href='/mapveto?id=$id' class='btn btn-primary'>Manage</a>                              
                                        <form method='POST' style='display:inline'>
                                            <input type='hidden' name='type' value='setactive'>
                                            <input type='hidden' name='id' value='$id'>
                                            <button type='submit' class='btn btn-primary' " . ($active == 1 ? "disabled" : "") . ">
                                                Set Active
                                            </button>
                                        </form>     
                                        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#delete$id'>Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='modal fade' id='delete$id' tabindex='-1' aria-labelledby='deletelabel$id' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='deletelabel$id'>Are you sure you wish to delete $title?</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <p>This action cannot be undone!</p>
                                        <form method='POST' enctype='multipart/form-data'>
                                            <input type='hidden' name='type' value='delete'>
                                            <input type='hidden' name='id' value='$id'>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='submit' class='btn btn-primary' value='Submit'>Delete $title</button>
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