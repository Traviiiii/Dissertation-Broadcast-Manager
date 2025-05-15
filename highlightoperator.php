<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}

include("php/connection.php");

// Select current highlight
$query = "SELECT operator FROM settings LIMIT 1";
$stmt = $Conn->prepare($query);
$stmt->execute();
$currentOperator = $stmt->fetchColumn();

// Update highlight
if (isset($_POST["type"])) {
    $type = $_POST['type'];

    if ($type == 'operator') {
        $operator = $_POST['operator'];

        $query = "UPDATE settings SET operator = :operator";
        $stmt = $Conn->prepare($query);
        $stmt->execute([':operator' => $operator]);

        $currentOperator = $operator;
    } else {
        echo '<script>alert("Invalid form type submitted.")</script>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Highlight Operator</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php include "php/navbar.php" ?>

    <div class="col-md-10">
        <div class="container py-5">

            <h1 class="text-center">Operator Highlight</h1>
            <hr class="bg-light border-2 border-top border-dark">

            <form method='POST' enctype='multipart/form-data'>
                <input type='hidden' name='type' value='operator'>
                <div class='mb-3'>
                    <label for='operator' class='form-label'>Operator Highlight Option</label>
                    <select id='operator' name='operator' class='form-select'>
                    </select>
                </div>
                <button type='submit' class='btn btn-primary'>Update</button>
            </form>

            <hr class="bg-light border-2 border-top border-dark">

            <h2 id="currentSelection" class="text-center">Current Selection: <span id="opName">Sledge</span></h2>

            <div class="row">
                <div class="col-md-4">
                    <img class="operator-image img-fluid" src="https://r6-game-data-api.onrender.com/uploads/2v16ddp5rho1732096010677-imgsledge.png">
                </div>
                <div class="col-md-8 text-uppercase">
                    <img class="operator-icon" src="https://r6-game-data-api.onrender.com/uploads/0vjajo4p1r1732096010676-iconsledge.png">
                    <h5>Primary Weapons: <span id="primaryWeapon1"></span> <span id="primaryWeapon2"></span> <span id="primaryWeapon3"></span></h5>
                    <h5>Secondary Weapons: <span id="secondaryWeapon1"></span> <span id="secondaryWeapon2"></span> <span id="secondaryWeapon3"></span></h5>
                    <h5>Gadgets: <span id="gadget1"></span> <span id="gadget2"></span> <span id="gadget3"></span></h5>
                </div>
            </div>

        </div>
    </div>

    <script src="js/manager.js"></script>
    <script src="js/operatoroptions.js"></script>
    <script>
        const apiUrl = 'https://r6-game-data-api.onrender.com/operator/get/all';

        fetch(apiUrl)
            .then(response => response.json())
            .then(operators => {
                const currentOperator = "<?php echo htmlspecialchars($currentOperator, ENT_QUOTES, 'UTF-8'); ?>";
                const selectedOperator = operators.find(operator => operator.id === currentOperator);

                if (selectedOperator) {
                    document.getElementById('opName').textContent = selectedOperator.name;

                    const primaryWeapons = [selectedOperator.primary_1, selectedOperator.primary_2, selectedOperator.primary_3];
                    const secondaryWeapons = [selectedOperator.secondary_1, selectedOperator.secondary_2, selectedOperator.secondary_3];
                    const gadgets = [selectedOperator.gadget_1, selectedOperator.gadget_2, selectedOperator.gadget_3];
                    
                    ['primaryWeapon1', 'primaryWeapon2', 'primaryWeapon3'].forEach((id, index) => {
                        if (primaryWeapons[index]) {
                            document.getElementById(id).textContent = primaryWeapons[index];
                        }
                    });
                    ['secondaryWeapon1', 'secondaryWeapon2', 'secondaryWeapon3'].forEach((id, index) => {
                        if (secondaryWeapons[index]) {
                            document.getElementById(id).textContent = secondaryWeapons[index];
                        }
                    });
                    ['gadget1', 'gadget2', 'gadget3'].forEach((id, index) => {
                        if (gadgets[index]) {
                            document.getElementById(id).textContent = gadgets[index];
                        }
                    });
                    document.querySelector('.operator-image').src = `https://r6-game-data-api.onrender.com/uploads/${selectedOperator.image}`;
                    document.querySelector('.operator-icon').src = `https://r6-game-data-api.onrender.com/uploads/${selectedOperator.icon}`;
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>

</body>

</html>
