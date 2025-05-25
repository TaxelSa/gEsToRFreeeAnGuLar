<?php
require_once "../php/db.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['codigo_equipo'], $data['numero_control'])) {
    echo json_encode(["error" => "Faltan datos para unirse al equipo"]);
    exit;
}

$codigo = $data['codigo_equipo'];
$numero_control = $data['numero_control'];

try {
    // Verificar si el usuario ya está en el equipo
    $checkSql = "SELECT COUNT(*) FROM equipo_integrante 
                 WHERE codigo_equipo = ? AND numero_control = ?";
    
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$codigo, $numero_control]);
    $existe = $checkStmt->fetchColumn();

    if ($existe) {
        echo json_encode(["error" => "El usuario ya forma parte del equipo"]);
        exit;
    }

    // Insertar al usuario en el equipo
    $sql = "INSERT INTO equipo_integrante (codigo_equipo, numero_control) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$codigo, $numero_control]);

    echo json_encode(["mensaje" => "Te has unido al equipo exitosamente"]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error al unirse al equipo: " . $e->getMessage()]);
}
