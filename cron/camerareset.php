<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$time = (int)date('G');

if ($time >= 1 && $time < 3) {
    include("../php/connection.php");

    $query = "DELETE FROM cameras";
    $stmt = $Conn->prepare($query);
    $stmt->execute();
}