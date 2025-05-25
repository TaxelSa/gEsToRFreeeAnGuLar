<?php
// Cabeceras para permitir CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("db.php"); // Conexión a la base de datos

// Leer los datos JSON recibidos
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id_proyecto)) {
    echo json_encode(["error" => "ID del proyecto no proporcionado"]);
    exit();
}

$id_proyecto = htmlspecialchars(strip_tags($data->id_proyecto));

try {
    // Eliminar el proyecto por su ID
    $sql = "DELETE FROM proyecto WHERE id_proyecto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_proyecto]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["mensaje" => "✅ Proyecto eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "❌ No se encontró el proyecto con ese ID"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error al eliminar el proyecto: " . $e->getMessage()]);
}
?>
