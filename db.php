<?php

require_once __DIR__ . '/helpers.php';

// Configuracion de la base de datos
$host = app_env('DB_HOST', 'db');
$user = app_env('DB_USERNAME', 'app_user');
$pass = app_env('DB_PASSWORD', 'secret');
$db   = app_env('DB_DATABASE', 'app_db');

// Crear la conexion
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    error_log("Fallo de conexion: " . $conn->connect_error);
    header("Content-Type: application/json");
    echo json_encode(["success" => false, "msg" => "No se pudo conectar con el servidor"]);
    exit;
}

if (!$conn->set_charset("utf8mb4")) {
    error_log("Error cargando el conjunto de caracteres utf8mb4: " . $conn->error);
}
