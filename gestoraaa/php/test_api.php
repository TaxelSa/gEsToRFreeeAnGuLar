<?php
// Enable error reporting for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test data
$testData = [
    'nombre_tarea' => 'Tarea de prueba ' . time(),
    'fecha_entrega' => date('Y-m-d', strtotime('+1 day')),
    'hora_entrega' => '15:00',
    'estado' => 'pendiente'
];

// Initialize cURL
$ch = curl_init('http://127.0.0.1/gEsToRFreeeAnGuLar/gestoraaa/php/agregar_tarea.php');

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

// Execute the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if (curl_errno($ch)) {
    die('cURL Error: ' . curl_error($ch));
}

// Close cURL
curl_close($ch);

// Decode the response
$result = json_decode($response, true);

// Display results
echo "<h1>API Test Results</h1>";
echo "<h2>Request:</h2>";
echo "<pre>" . json_encode($testData, JSON_PRETTY_PRINT) . "</pre>";

echo "<h2>Response (HTTP {$httpCode}):</h2>";
echo "<pre>" . json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

// Display raw response for debugging
echo "<h3>Raw Response:</h3>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// Add a link to view all tasks
echo "<p><a href='listar_tareas.php'>Ver todas las tareas</a></p>";
?>
