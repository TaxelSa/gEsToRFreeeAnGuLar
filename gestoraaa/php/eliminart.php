<?php
// Habilitar CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Si es una solicitud OPTIONS, responder inmediatamente
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configurar el tipo de contenido
header('Content-Type: application/json');

// Habilitar la salida de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si la petición viene del frontend
$allowed_origins = ['http://localhost:5173', 'http://localhost'];
if (!isset($_SERVER['HTTP_ORIGIN']) || !in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Origen no permitido']);
    exit;
}

// Conexión a la base de datos
$host = 'localhost';
$db = 'gp_base';
$user = 'root';
$pass = ''; // Asegúrate de cambiar esto a una contraseña segura en producción

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de conexión: ' . $e->getMessage(),
        'details' => [
            'host' => $host,
            'db' => $db,
            'error_code' => $e->getCode()
        ]
    ]);
    exit;
}

// Verificar si se recibió el id_tarea por POST
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'No se recibieron datos en el cuerpo de la petición'
    ]);
    exit;
}

if (!isset($data['id_tarea'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'El campo id_tarea es requerido'
    ]);
    exit;
}

$id_tarea = $data['id_tarea'];

// Verificar si la tarea existe antes de eliminar
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tareas WHERE id_tarea = :id_tarea");
$stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
$stmt->execute();
$count = $stmt->fetchColumn();

if ($count === 0) {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'No se encontró la tarea con el ID especificado'
    ]);
    exit;
}

// Preparar y ejecutar la consulta de eliminación
$sql = "DELETE FROM tareas WHERE id_tarea = :id_tarea";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);

try {
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tarea eliminada correctamente',
            'rows_affected' => $stmt->rowCount()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar la tarea',
            'error' => $pdo->errorInfo()
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la ejecución de la consulta',
        'details' => $e->getMessage()
    ]);
}
?>
