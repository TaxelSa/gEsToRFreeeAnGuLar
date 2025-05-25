<?php
// Cabeceras para permitir CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("db.php"); // Conexión a la base de datos

// Leer los datos JSON recibidos
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id_proyecto, $data->nombre_proyecto, $data->descripcion, $data->fecha_entrega, $data->id_usuario, $data->id_estado, $data->id_materia, $data->id_equipo)) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit();
}

// Limpiar datos
$id_proyecto = htmlspecialchars(strip_tags($data->id_proyecto));
$nombre_proyecto = htmlspecialchars(strip_tags($data->nombre_proyecto));
$descripcion = htmlspecialchars(strip_tags($data->descripcion));
$fecha_entrega = htmlspecialchars(strip_tags($data->fecha_entrega));
$id_usuario = htmlspecialchars(strip_tags($data->id_usuario));
$id_estado = htmlspecialchars(strip_tags($data->id_estado));
$id_materia = htmlspecialchars(strip_tags($data->id_materia));
$id_equipo = htmlspecialchars(strip_tags($data->id_equipo));

try {
    // Verificar si el proyecto existe
    $checkSql = "SELECT COUNT(*) FROM proyecto WHERE id_proyecto = ?";
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute([$id_proyecto]);
    $existe = $stmt->fetchColumn();

    if (!$existe) {
        echo json_encode(["error" => "El ID del proyecto no existe"]);
        exit();
    }

    // Actualizar el proyecto
    $sql = "UPDATE proyecto SET nombre_proyecto=?, descripcion=?, fecha_entrega=?, id_usuario=?, id_estado=?, id_materia=?, id_equipo=? WHERE id_proyecto=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre_proyecto, $descripcion, $fecha_entrega, $id_usuario, $id_estado, $id_materia, $id_equipo, $id_proyecto]);

    echo json_encode(["mensaje" => "✅ Proyecto actualizado correctamente"]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Error al actualizar el proyecto: " . $e->getMessage()]);
}
?>
