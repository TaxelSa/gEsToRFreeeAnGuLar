<?php
// Configuración de la base de datos
$host = 'localhost';
$username = 'root';
$password = '';

// Crear conexión sin seleccionar una base de datos
try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear la base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS gestor_tareas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);
    
    // Seleccionar la base de datos
    $conn->exec("USE gestor_tareas");
    
    // Crear tabla de tareas si no existe
    $sql = "CREATE TABLE IF NOT EXISTS tareas (
        id_tarea INT AUTO_INCREMENT PRIMARY KEY,
        nombre_tarea VARCHAR(255) NOT NULL,
        fecha_entrega DATE NOT NULL,
        hora_entrega TIME NOT NULL,
        estado ENUM('pendiente', 'en_progreso', 'terminada') NOT NULL DEFAULT 'pendiente',
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->exec($sql);
    
    echo "Base de datos y tabla creadas exitosamente. Ya puedes cerrar esta pestaña.";
    
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
