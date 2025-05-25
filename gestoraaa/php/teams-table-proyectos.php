<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("../php/db.php");  // Conexión a la base de datos

try {
    $query = "SELECT * FROM Proyecto";
    $result = $pdo->query($query);

    $equipos = $result->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($equipos);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error al obtener los equipos: " . $e->getMessage()]);
}
?>

