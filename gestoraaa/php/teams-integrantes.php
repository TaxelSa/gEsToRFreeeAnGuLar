<?php
require_once "../php/db.php";

header("Content-Type: application/json");

if (!isset($_GET['codigo'])) {
    echo json_encode(["error" => "Falta el código del equipo"]);
    exit;
}

$codigo = $_GET['codigo'];

try {
    $sql = "SELECT u.numero_control, u.nombre, u.apellido 
            FROM usuario u
            JOIN equipo_integrante ei ON u.numero_control = ei.numero_control
            WHERE ei.codigo_equipo = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$codigo]);
    
    $integrantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($integrantes);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error al obtener integrantes: " . $e->getMessage()]);
}
