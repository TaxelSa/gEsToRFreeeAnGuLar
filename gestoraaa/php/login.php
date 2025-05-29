<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

require_once "db.php"; // Aquí se define $pdo

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (empty($input["numero_control"]) || empty($input["password"])) {
    $response["message"] = "Completa los campos";
    echo json_encode($response);
    exit;
}

$usuario = (int) $input["numero_control"];
$clave = $input["password"];

try {
    $stmt = $pdo->prepare("SELECT numero_control, nombre, apellido FROM Usuario WHERE numero_control = ? AND password = ?");
    $stmt->execute([$usuario, $clave]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION["numero_control"] = $row["numero_control"];
        $_SESSION["nombre_completo"] = $row["nombre"] . " " . $row["apellido"];

        $response["success"] = true;
        $response["message"] = "Inicio de sesión exitoso";
    } else {
        $response["message"] = "Número de control o contraseña incorrectos";
    }
} catch (PDOException $e) {
    $response["message"] = "Error de base de datos: " . $e->getMessage();
}

echo json_encode($response);
