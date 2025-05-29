<?php
// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Si es una solicitud OPTIONS, responder inmediatamente
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configurar el tipo de contenido
header('Content-Type: application/json');

// Conexión a la base de datos
$host = 'localhost';
$db = 'gp_base';
$user = 'root';
$pass = ''; // Cambia esto si usas una contraseña real

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de conexión: ' . $e->getMessage()
    ]);
    exit;
}

// Leer datos JSON del cuerpo de la solicitud DELETE
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_tarea'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'El campo id_tarea es requerido'
    ]);
    exit;
}

$id_tarea = (int)$data['id_tarea'];

// Verificar si la tarea existe
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tareas WHERE id_tarea = :id_tarea");
$stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->fetchColumn() == 0) {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'No se encontró la tarea con el ID especificado'
    ]);
    exit;
}

// Ejecutar la eliminación
$stmt = $pdo->prepare("DELETE FROM tareas WHERE id_tarea = :id_tarea");
$stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);

try {
    $stmt->execute();
    echo json_encode([
        'status' => 'success',
        'message' => 'Tarea eliminada correctamente'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al eliminar la tarea: ' . $e->getMessage()
    ]);
}
?>
