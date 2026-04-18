<?php
session_start();

header("Content-Type: application/json");

require_once 'db.php';
require_once 'helpers.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!is_array($data)) {
    echo json_encode(["success" => false, "msg" => "Datos no recibidos o JSON malformado"]);
    exit;
}

$action = $data['action'] ?? '';

if ($action === 'login') {
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if ($email === '' || $password === '') {
        echo json_encode(["success" => false, "msg" => "Email y contrasena requeridos"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, password, nombres FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombres'];

            echo json_encode([
                "success" => true,
                "msg" => "Bienvenido",
                "user" => $user['nombres']
            ]);
        } else {
            echo json_encode(["success" => false, "msg" => "Contrasena incorrecta"]);
        }
    } else {
        echo json_encode(["success" => false, "msg" => "El correo no esta registrado"]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

if ($action === 'register') {
    $nombres = trim($data['nombres'] ?? '');
    $apellidos = trim($data['apellidos'] ?? '');
    $email = trim($data['email'] ?? '');
    $numero = app_normalize_phone($data['numero'] ?? '');
    $password = $data['password'] ?? '';

    if ($nombres === '' || $email === '' || $password === '') {
        echo json_encode(["success" => false, "msg" => "Campos obligatorios faltantes"]);
        exit;
    }

    $checkEmail = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();

    if ($checkEmail->get_result()->num_rows > 0) {
        echo json_encode(["success" => false, "msg" => "Este correo ya esta registrado"]);
        $checkEmail->close();
        exit;
    }

    $checkEmail->close();

    if ($numero !== '') {
        $checkPhone = $conn->prepare("SELECT id FROM usuarios WHERE numero = ?");
        $checkPhone->bind_param("s", $numero);
        $checkPhone->execute();

        if ($checkPhone->get_result()->num_rows > 0) {
            echo json_encode(["success" => false, "msg" => "Este numero ya esta registrado"]);
            $checkPhone->close();
            exit;
        }

        $checkPhone->close();
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $imagenDefecto = 'perfil.png';
    $cedulaDefecto = '00000000000';
    $resenaDefecto = 'Sin descripcion personal.';

    $stmt = $conn->prepare(
        "INSERT INTO usuarios (nombres, apellidos, email, numero, password, imagen, cedula, resena_personal)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssssss",
        $nombres,
        $apellidos,
        $email,
        $numero,
        $passwordHash,
        $imagenDefecto,
        $cedulaDefecto,
        $resenaDefecto
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "msg" => "Registro exitoso. Ya puedes iniciar sesion."]);
    } else {
        echo json_encode(["success" => false, "msg" => "Error interno al registrar: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(["success" => false, "msg" => "Accion no valida"]);
$conn->close();
