<?php
// Iniciar sesión antes de cualquier salida de texto
session_start(); 

header("Content-Type: application/json");

// Importar conexión a la base de datos
// Asegúrate de que $conn esté definido en db.php
require_once 'db.php';

// Leer el cuerpo de la petición (JSON)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["success" => false, "msg" => "Datos no recibidos o JSON malformado"]);
    exit;
}

$action = $data['action'] ?? '';

// --- LÓGICA DE LOGIN ---
if ($action === 'login') {
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "msg" => "Email y contraseña requeridos"]);
        exit;
    }

    // Consulta preparada para evitar Inyección SQL
    $stmt = $conn->prepare("SELECT id, password, nombres FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verificar el hash de la contraseña
        if (password_verify($password, $user['password'])) {
            
            // Guardar datos en la sesión del servidor
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['user_name'] = $user['nombres'];

            echo json_encode([
                "success" => true, 
                "msg" => "¡Bienvenido!",
                "user" => $user['nombres']
            ]);
        } else {
            echo json_encode(["success" => false, "msg" => "Contraseña incorrecta"]);
        }
    } else {
        echo json_encode(["success" => false, "msg" => "El correo no está registrado"]);
    }
    $stmt->close();
} 

// --- LÓGICA DE REGISTRO ---
else if ($action === 'register') {
    $nombres  = trim($data['nombres'] ?? '');
    $apellidos = trim($data['apellidos'] ?? '');
    $email     = trim($data['email'] ?? '');
    $numero    = trim($data['numero'] ?? '');
    $password  = $data['password'] ?? '';

    // Validaciones de campos obligatorios
    if (empty($nombres) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "msg" => "Campos obligatorios faltantes (nombre, email o contraseña)"]);
        exit;
    }

    // Verificar si el correo ya existe para evitar duplicados
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(["success" => false, "msg" => "Este correo ya está registrado"]);
        $check->close();
        exit;
    }
    $check->close();

    // Encriptar la contraseña (BCRYPT es el estándar actual)
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    
    // Valores por defecto para campos requeridos por la estructura de tu DB
    $imagenDefecto = 'perfil.png';
    $cedulaDefecto = '00000000000';
    $resenaDefecto = 'Sin descripción personal.';

    // CORRECCIÓN: Tu código original tenía 8 campos en el INSERT pero 7 "s" en bind_param
    // Campos: nombres(1), apellidos(2), email(3), numero(4), password(5), imagen(6), cedula(7), resena_personal(8)
    $stmt = $conn->prepare("INSERT INTO usuarios (nombres, apellidos, email, numero, password, imagen, cedula, resena_personal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    // "ssssssss" -> 8 strings
    $stmt->bind_param("ssssssss", $nombres, $apellidos, $email, $numero, $passwordHash, $imagenDefecto, $cedulaDefecto, $resenaDefecto);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "msg" => "Registro exitoso. Ya puedes iniciar sesión."]);
    } else {
        echo json_encode(["success" => false, "msg" => "Error interno al registrar: " . $conn->error]);
    }
    $stmt->close();
}

// Acción desconocida
else {
    echo json_encode(["success" => false, "msg" => "Acción no válida"]);
}

$conn->close();
?>