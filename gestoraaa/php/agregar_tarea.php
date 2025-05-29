<?php
// Disable error display in production
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start output buffering to catch any unwanted output
ob_start();

// Include database connection
require_once 'conexion.php';

// Enable CORS and set headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Initialize response array
$response = [
    'status' => 'error',
    'message' => '',
    'data' => null
];

try {
    // Get and validate JSON input
    $json = file_get_contents('php://input');
    if (empty($json)) {
        throw new Exception('No se recibieron datos');
    }

    $data = json_decode($json);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Formato JSON inválido: ' . json_last_error_msg());
    }

    // Check if required fields are present
    if (!is_object($data)) {
        throw new Exception('Datos de entrada no válidos');
    }

    $requiredFields = ['nombre_tarea', 'fecha_entrega', 'hora_entrega', 'estado'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (empty($data->$field)) {
            $missingFields[] = $field;
        }
    }

    if (!empty($missingFields)) {
        throw new Exception('Faltan campos requeridos: ' . implode(', ', $missingFields));
    }

    // Sanitize and validate input
    $nombre_tarea = filter_var($data->nombre_tarea, FILTER_SANITIZE_STRING);
    $fecha_entrega = $data->fecha_entrega;
    $hora_entrega = $data->hora_entrega;
    $estado = in_array($data->estado, ['pendiente', 'en_progreso', 'terminada']) ? $data->estado : 'pendiente';

    // Validate date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_entrega)) {
        throw new Exception('Formato de fecha inválido. Use YYYY-MM-DD');
    }

    // Validate time format (HH:MM)
    if (!preg_match('/^\d{2}:\d{2}$/', $hora_entrega)) {
        throw new Exception('Formato de hora inválido. Use HH:MM');
    }

    // Prepare and execute the query
    $sql = "INSERT INTO tarea (nombre_tarea, fecha_entrega, hora_entrega, estado) 
            VALUES (:nombre_tarea, :fecha_entrega, :hora_entrega, :estado)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':nombre_tarea', $nombre_tarea, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_entrega', $fecha_entrega, PDO::PARAM_STR);
    $stmt->bindParam(':hora_entrega', $hora_entrega, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Get the ID of the newly created task
        $id_tarea = $conn->lastInsertId();

        // Fetch the created task
        $stmt = $conn->prepare("SELECT * FROM tarea WHERE id_tarea = :id_tarea");
        $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
        $stmt->execute();
        $tarea = $stmt->fetch(PDO::FETCH_ASSOC);

        $response = [
            'status' => 'success',
            'message' => 'Tarea creada exitosamente',
            'data' => $tarea
        ];
        http_response_code(201);
    } else {
        throw new Exception('No se pudo crear la tarea');
    }

} catch (PDOException $e) {
    $response['message'] = 'Error de base de datos: ' . $e->getMessage();
    http_response_code(500);
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// Clean any output before sending JSON
ob_end_clean();

// Set JSON header and encode response
header('Content-Type: application/json');
$jsonResponse = json_encode($response, JSON_UNESCAPED_UNICODE);

// Verify JSON encoding was successful
if ($jsonResponse === false) {
    $jsonResponse = json_encode([
        'status' => 'error',
        'message' => 'Error al codificar la respuesta JSON: ' . json_last_error_msg(),
        'data' => null
    ]);
}

echo $jsonResponse;

// Close the database connection if it exists
if (isset($conn)) {
    $conn = null;
}

exit();
?>
