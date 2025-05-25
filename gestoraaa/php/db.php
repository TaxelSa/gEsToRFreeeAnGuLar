<?php
$host = "localhost";
$dbname = "gp_base";   // Asegúrate que el nombre sea correcto
$user = "root";
$pass = "";  // Contraseña (si tienes)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
