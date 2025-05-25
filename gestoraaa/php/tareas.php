<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'gp_base';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener todas las tareas
    $stmt = $pdo->prepare("SELECT * FROM tarea");
    $stmt->execute();
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log para depuración
    error_log("Tareas encontradas: " . print_r($tareas, true));
    
    // Devolver las tareas en formato JSON
    echo json_encode([
        'status' => 'success',
        'data' => $tareas
    ]);

} catch(PDOException $e) {
    error_log("Error en la base de datos: " . $e->getMessage());
    // En caso de error, devolver mensaje de error
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()
    ]);
}
?>
