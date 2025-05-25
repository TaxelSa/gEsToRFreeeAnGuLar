<?php
// Cabeceras para permitir CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

include("db.php"); // Conexión a la base de datos

// Obtener el código del equipo desde la URL
$codigo_equipo = isset($_GET['codigo_equipo']) ? $_GET['codigo_equipo'] : null;

if (!$codigo_equipo) {
    echo json_encode(["error" => "Código de equipo no proporcionado"]);
    exit();
}

try {
    $sql = "DELETE FROM equipo WHERE codigo_equipo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$codigo_equipo]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["mensaje" => "Equipo eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "No se encontró el equipo con el código proporcionado"]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => "Error al eliminar el equipo: " . $e->getMessage()]);
}
?>