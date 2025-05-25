<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

if (isset($_SESSION["numero_control"])) {
    echo json_encode([
        "logged_in" => true,
        "numero_control" => $_SESSION["numero_control"],
        "nombre_completo" => $_SESSION["nombre_completo"]
    ]);
} else {
    echo json_encode(["logged_in" => false]);
}
?>

