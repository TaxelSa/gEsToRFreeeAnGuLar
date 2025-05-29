<?php
// Enable error reporting for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once 'conexion.php';

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

try {
    // Query to get all tasks
    $stmt = $conn->query("SELECT * FROM tarea ORDER BY fecha_creacion DESC");
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    echo json_encode([
        'status' => 'success',
        'count' => count($tareas),
        'data' => $tareas
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // Log the error
    error_log('Error al listar tareas: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener las tareas',
        'error' => [
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

// Close the database connection
$conn = null;
?>

<!-- HTML for browser view -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        .task { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 4px; }
        .task h3 { margin-top: 0; }
        .meta { color: #666; font-size: 0.9em; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Lista de Tareas</h1>
    <div id="tasks">
        <p>Cargando tareas...</p>
    </div>
    
    <p><a href="test_api.php">Probar API de creación</a></p>
    
    <script>
        // Fetch tasks using JavaScript for a better user experience
        fetch('listar_tareas.php')
            .then(response => response.json())
            .then(data => {
                const tasksContainer = document.getElementById('tasks');
                
                if (data.status === 'success') {
                    if (data.count === 0) {
                        tasksContainer.innerHTML = '<p>No hay tareas registradas.</p>';
                        return;
                    }
                    
                    let html = '';
                    data.data.forEach(task => {
                        html += `
                            <div class="task">
                                <h3>${task.nombre_tarea}</h3>
                                <div class="meta">
                                    <p><strong>Fecha de entrega:</strong> ${task.fecha_entrega} a las ${task.hora_entrega}</p>
                                    <p><strong>Estado:</strong> ${task.estado}</p>
                                    <p><small>Creada el: ${task.fecha_creacion}</small></p>
                                </div>
                            </div>
                        `;
                    });
                    
                    tasksContainer.innerHTML = html;
                } else {
                    tasksContainer.innerHTML = `<p class="error">Error: ${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('tasks').innerHTML = 
                    '<p class="error">Error al cargar las tareas. Por favor, intente de nuevo.</p>';
            });
    </script>
</body>
</html>
