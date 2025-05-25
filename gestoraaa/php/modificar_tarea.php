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

if (!isset($data['id_tarea']) || !isset($data['nombre_tarea']) || !isset($data['descripcion']) || !isset($data['fecha_entrega']) || !isset($data['hora_entrega']) || !isset($data['prioridad']) || !isset($data['id_estado'])) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode([
        'status' => 'error',
        'message' => 'Todos los campos son obligatorios'
    ]);
    exit;
}

$id_tarea = $data['id_tarea'];
$nombre_tarea = $data['nombre_tarea'];
$descripcion = $data['descripcion'];
$fecha_entrega = $data['fecha_entrega'];
$hora_entrega = $data['hora_entrega'];
$prioridad = $data['prioridad'];
$id_estado = $data['id_estado'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE tarea SET 
                nombre_tarea = :nombre_tarea, 
                descripcion = :descripcion, 
                fecha_entrega = :fecha_entrega, 
                hora_entrega = :hora_entrega, 
                prioridad = :prioridad, 
                id_estado = :id_estado 
            WHERE id_tarea = :id_tarea";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
    $stmt->bindParam(':nombre_tarea', $nombre_tarea, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_entrega', $fecha_entrega, PDO::PARAM_STR);
    $stmt->bindParam(':hora_entrega', $hora_entrega, PDO::PARAM_STR);
    $stmt->bindParam(':prioridad', $prioridad, PDO::PARAM_INT);
    $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tarea modificada exitosamente'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al modificar la tarea'
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