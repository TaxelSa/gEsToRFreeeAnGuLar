<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'gestor_tareas';

echo "<h2>Testing MySQL Connection</h2>";

try {
    // Test connection to MySQL server
    echo "<p>Attempting to connect to MySQL server...</p>";
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green;'>✓ Successfully connected to MySQL server</p>";
    
    // Check if database exists
    echo "<p>Checking if database exists...</p>";
    $stmt = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    $databaseExists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($databaseExists) {
        echo "<p style='color:green;'>✓ Database '$dbname' exists</p>";
        
        // Check tables
        $conn->exec("USE `$dbname`");
        $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        if (in_array('tareas', $tables)) {
            echo "<p style='color:green;'>✓ Table 'tareas' exists</p>";
            
            // Show table structure
            $columns = $conn->query("DESCRIBE tareas")->fetchAll(PDO::FETCH_ASSOC);
            echo "<h3>Table Structure:</h3><pre>";
            print_r($columns);
            echo "</pre>";
            
            // Test insert
            try {
                $testData = [
                    'nombre_tarea' => 'Tarea de prueba',
                    'fecha_entrega' => date('Y-m-d'),
                    'hora_entrega' => '12:00',
                    'estado' => 'pendiente'
                ];
                
                $sql = "INSERT INTO tareas (nombre_tarea, fecha_entrega, hora_entrega, estado) 
                        VALUES (:nombre_tarea, :fecha_entrega, :hora_entrega, :estado)";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute($testData);
                $id = $conn->lastInsertId();
                
                echo "<p style='color:green;'>✓ Test record inserted successfully (ID: $id)</p>";
                
                // Clean up
                $conn->exec("DELETE FROM tareas WHERE id_tarea = $id");
                
            } catch (Exception $e) {
                echo "<p style='color:red;'>✗ Error inserting test record: " . $e->getMessage() . "</p>";
            }
            
        } else {
            echo "<p style='color:red;'>✗ Table 'tareas' does not exist</p>";
        }
        
    } else {
        echo "<p style='color:red;'>✗ Database '$dbname' does not exist</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
    
    // Show connection details for debugging
    echo "<h3>Connection Details:</h3>";
    echo "<pre>";
    echo "Host: $host\n";
    echo "Username: $username\n";
    echo "Password: " . ($password ? '***' : '(empty)') . "\n";
    echo "Database: $dbname\n";
    echo "</pre>";
}
?>

<h3>Next Steps:</h3>
<ol>
    <li>If you see any red errors, please check your MySQL server is running</li>
    <li>Make sure the database user has the correct permissions</li>
    <li>Check that the database and table exist with the correct structure</li>
    <li>If the test record fails to insert, there might be an issue with the table structure</li>
</ol>
