

<?php









// Configuración de la base de datos
$host = "db";
$user = "app_user"; 
$pass = "secret"; 
$db   = "app_db";

// Crear la conexión
$conn = new mysqli($host, $user, $pass, $db);

// 1. Verificar si hay errores de conexión
if ($conn->connect_error) {
    // Log del error para el administrador (opcional)
    error_log("Fallo de conexión: " . $conn->connect_error);
    
    // Respuesta genérica para el frontend (seguridad)
    header("Content-Type: application/json");
    echo json_encode(["success" => false, "msg" => "No se pudo conectar con el servidor"]);
    exit;
}

// 2. Establecer el charset a utf8mb4 (soporta emojis y caracteres especiales)
if (!$conn->set_charset("utf8mb4")) {
    error_log("Error cargando el conjunto de caracteres utf8mb4: " . $conn->error);
}

// Nota: No cerramos la conexión aquí porque este archivo se incluye en otros 
// donde la variable $conn es necesaria.
?>