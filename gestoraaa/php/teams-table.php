<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("../php/db.php");  // Conexión a la base de datos

$query = "SELECT * FROM equipo";
$result = $pdo->query($query);

$equipos = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $equipos[] = $row;
}

echo json_encode($equipos);
