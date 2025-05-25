<?php
header('Access-Control-Allow-Origin: http://localhost:5173'); // Cambia esto al origen de tu frontend
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Manejar solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
    exit;
}

$host = 'localhost';
$dbname = 'gp_base';
$username = 'root';
$password = '';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_tarea'])) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode([
        'status' => 'error',
        'message' => 'El ID de la tarea es obligatorio'
    ]);
    exit;
}

$id_tarea = $data['id_tarea'];
error_log("ID de tarea recibido: $id_tarea"); // Agrega este log para verificar el ID recibido

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DELETE FROM tarea WHERE id_tarea = :id_tarea";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tarea eliminada exitosamente'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar la tarea'
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500); // Error interno del servidor
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()
    ]);
}
?>