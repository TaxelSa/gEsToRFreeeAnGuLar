<?php
// Disable error display in production
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Database configuration
$host = 'localhost';          // Database host (usually localhost)
$dbname = 'gp_base
';    // Database name
$username = 'root';           // Default XAMPP username
$password = '';               // Default XAMPP password is empty

// Initialize connection variable
$conn = null;

try {
    // Set PDO error mode to exception
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
    ];

    // First try to connect without selecting a database
    $conn = new PDO("mysql:host=$host", $username, $password, $options);

    // Check if database exists, if not create it
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname`
                CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci");

    // Now select the database
    $conn->exec("USE `$dbname`");

    // Set timezone
    $conn->exec("SET time_zone = '-06:00'");

    // Create tables if they don't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS `tarea` (
        `id_tarea` INT AUTO_INCREMENT PRIMARY KEY,
        `nombre_tarea` VARCHAR(255) NOT NULL,
        `fecha_entrega` DATE NOT NULL,
        `hora_entrega` TIME NOT NULL,
        `estado` ENUM('pendiente', 'en_progreso', 'terminada') NOT NULL DEFAULT 'pendiente',
        `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

} catch(PDOException $e) {
    // Log the error
    error_log('Database Error: ' . $e->getMessage());

    // If we're in a web context, send a clean error response
    if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error de conexión con la base de datos',
            'error' => [
                'code' => $e->getCode(),
                'message' => 'No se pudo conectar a la base de datos. Por favor, intente más tarde.'
            ]
        ]);
    } else {
        // For non-API requests, show a simple error message
        die('Error de conexión con la base de datos. Por favor, intente más tarde.');
    }
    exit();
}
?>
