<?php
// Cabeceras para permitir CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("db.php");  // Conexión a la base de datos

// Leer los datos JSON recibidos
$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->codigo_equipo, $data->nombre_equipo, $data->descripcion, $data->numero_control)) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit();
}

// Limpiar datos
$codigo_equipo = htmlspecialchars(strip_tags($data->codigo_equipo));
$nombre_equipo = htmlspecialchars(strip_tags($data->nombre_equipo));
$descripcion = htmlspecialchars(strip_tags($data->descripcion));
$numero_control = htmlspecialchars(strip_tags($data->numero_control));

try {
    // Verificar si el equipo existe
    $checkSql = "SELECT COUNT(*) FROM equipo WHERE codigo_equipo = ?";
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute([$codigo_equipo]);
    $existe = $stmt->fetchColumn();

    if (!$existe) {
        echo json_encode(["error" => "El código de equipo no existe"]);
        exit();
    }

    // Actualizar los datos del equipo
    $sql = "UPDATE equipo SET nombre_equipo = ?, descripcion = ?, numero_control = ? WHERE codigo_equipo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre_equipo, $descripcion, $numero_control, $codigo_equipo]);

    echo json_encode(["mensaje" => "✅ Equipo actualizado correctamente"]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Error al actualizar el equipo: " . $e->getMessage()]);
}
?>
