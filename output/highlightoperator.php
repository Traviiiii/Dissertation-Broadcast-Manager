<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../php/connection.php");

$query = "SELECT operator FROM settings LIMIT 1";
$stmt = $Conn->prepare($query);
$stmt->execute();
$currentOperator = $stmt->fetchColumn();

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
    <title>TEBS - Output Page</title>
    <link rel="stylesheet" href="../css/output.css">

    <?php

    $query = "SELECT theme FROM settings LIMIT 1";
    $stmt = $Conn->prepare($query);
    $stmt->execute();
    $currentTheme = $stmt->fetchColumn();

    echo "<link rel='stylesheet' href='../themes/$currentTheme.css'>";

    ?>
</head>

<body>

    <div class="container highlight-container op-container">
        <img class="operator-img" src="https://r6-game-data-api.onrender.com/uploads/2v16ddp5rho1732096010677-imgsledge.png">
        <div style="margin-left: 10px; margin-right: 10px;" class="player-text">
            <img class="operator-icon" src="https://r6-game-data-api.onrender.com/uploads/0vjajo4p1r1732096010676-iconsledge.png">
            <h1 id="opName" style="text-align: center;">Sledge</h1>
            <h5><span class="text-bold">Primary Weapons:</span> <span id="primaryWeapon1"></span> <span id="primaryWeapon2"></span> <span id="primaryWeapon3"></span></h5>
            <h5><span class="text-bold">Secondary Weapons:</span> <span id="secondaryWeapon1"></span> <span id="secondaryWeapon2"></span> <span id="secondaryWeapon3"></span></h5>
            <h5><span class="text-bold">Gadgets:</span> <span id="gadget1"></span> <span id="gadget2"></span> <span id="gadget3"></span></h5>
        </div>
    </div>

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
                    document.querySelector('.operator-img').src = `https://r6-game-data-api.onrender.com/uploads/${selectedOperator.image}`;
                    document.querySelector('.operator-icon').src = `https://r6-game-data-api.onrender.com/uploads/${selectedOperator.icon}`;
                }
            })
            .catch(error => {
                console.error(error);
            });

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelector('.container').classList.add('loaded');
            }, 1000);
        });
    </script>


</body>

</html>