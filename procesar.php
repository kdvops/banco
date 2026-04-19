<?php
session_start();

header("Content-Type: application/json");

require_once 'db.php';
require_once 'helpers.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "msg" => "No autorizado"]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

function subirImagen(string $field, string $prefix = ''): ?string
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== 0) {
        return null;
    }

    $extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $baseName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES[$field]['name']));

    if ($baseName === '' || $baseName === null) {
        $baseName = 'archivo';
    }

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $fileName = time() . "_" . $prefix . $baseName;
    $destination = "uploads/" . $fileName;

    if ($extension === '') {
        $destination = "uploads/" . time() . "_" . $prefix . pathinfo($baseName, PATHINFO_FILENAME);
    }

    if (move_uploaded_file($_FILES[$field]['tmp_name'], $destination)) {
        return $destination;
    }

    return null;
}

if ($action === "perfil") {
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $cedula = preg_replace('/\D+/', '', $_POST['cedula'] ?? '') ?? '';
    $numero = app_normalize_phone($_POST['numero'] ?? '');
    $resena = trim($_POST['resena_personal'] ?? '');

    $stmtCurr = mysqli_prepare($conn, "SELECT imagen FROM usuarios WHERE id = ?");
    mysqli_stmt_bind_param($stmtCurr, "i", $user_id);
    mysqli_stmt_execute($stmtCurr);
    $resCurr = mysqli_stmt_get_result($stmtCurr);
    $userData = mysqli_fetch_assoc($resCurr);
    $fotoActual = $userData['imagen'] ?? 'perfil.png';
    mysqli_stmt_close($stmtCurr);

    if ($numero !== '') {
        $stmtPhone = mysqli_prepare($conn, "SELECT id FROM usuarios WHERE numero = ? AND id <> ?");
        mysqli_stmt_bind_param($stmtPhone, "si", $numero, $user_id);
        mysqli_stmt_execute($stmtPhone);
        $resPhone = mysqli_stmt_get_result($stmtPhone);

        if (mysqli_num_rows($resPhone) > 0) {
            echo json_encode(["status" => "error", "msg" => "Ese numero ya pertenece a otro usuario"]);
            mysqli_stmt_close($stmtPhone);
            exit;
        }

        mysqli_stmt_close($stmtPhone);
    }

    $nuevaFoto = subirImagen('foto', $user_id . "_");
    if ($nuevaFoto !== null) {
        $fotoActual = basename($nuevaFoto);
    }

    $stmtUpd = mysqli_prepare(
        $conn,
        "UPDATE usuarios SET nombres = ?, apellidos = ?, cedula = ?, numero = ?, resena_personal = ?, imagen = ? WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmtUpd, "ssssssi", $nombres, $apellidos, $cedula, $numero, $resena, $fotoActual, $user_id);

    if (mysqli_stmt_execute($stmtUpd)) {
        echo json_encode(["status" => "ok", "msg" => "Perfil actualizado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Error al actualizar: " . mysqli_stmt_error($stmtUpd)]);
    }

    mysqli_stmt_close($stmtUpd);
    exit;
}

if ($action === "servicio") {
    $nombre = trim($_POST['nombre_servicio'] ?? '');
    $resena = trim($_POST['resena'] ?? '');
    $enlace = trim($_POST['enlace'] ?? '');
    $imagen = subirImagen('imagen', $user_id . "_");

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO servicios (usuario_id, nombre_servicio, resena, enlace, imagen) VALUES (?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $nombre, $resena, $enlace, $imagen);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "ok", "tipo" => "servicio"]);
    } else {
        echo json_encode(["status" => "error", "msg" => mysqli_stmt_error($stmt)]);
    }

    mysqli_stmt_close($stmt);
    exit;
}

if ($action === "cuenta") {
    $bancoId = (int) ($_POST['banco_id'] ?? 0);
    $tipo = $_POST['tipo'] ?? '';
    $numero = trim($_POST['numero'] ?? '');

    if ($bancoId <= 0) {
        echo json_encode(["status" => "error", "msg" => "Selecciona un banco valido"]);
        exit;
    }

    $stmtBank = mysqli_prepare($conn, "SELECT id FROM bancos WHERE id = ? AND activo = 1 LIMIT 1");
    mysqli_stmt_bind_param($stmtBank, "i", $bancoId);
    mysqli_stmt_execute($stmtBank);
    $bankResult = mysqli_stmt_get_result($stmtBank);

    if (!$bankResult || !mysqli_fetch_assoc($bankResult)) {
        mysqli_stmt_close($stmtBank);
        echo json_encode(["status" => "error", "msg" => "El banco seleccionado no esta disponible"]);
        exit;
    }

    mysqli_stmt_close($stmtBank);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO cuentas_bancarias (usuario_id, banco_id, tipo_cuenta, numero_cuenta) VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "iiss", $user_id, $bancoId, $tipo, $numero);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "ok", "tipo" => "cuenta"]);
    } else {
        echo json_encode(["status" => "error", "msg" => mysqli_stmt_error($stmt)]);
    }

    mysqli_stmt_close($stmt);
    exit;
}

if ($action === "crypto") {
    $cryptoAssetId = (int) ($_POST['cripto_activo_id'] ?? 0);
    $direccion = trim($_POST['direccion'] ?? '');

    if ($cryptoAssetId <= 0) {
        echo json_encode(["status" => "error", "msg" => "Selecciona una criptomoneda valida"]);
        exit;
    }

    $stmtAsset = mysqli_prepare($conn, "SELECT id FROM cripto_activos WHERE id = ? AND activo = 1 LIMIT 1");
    mysqli_stmt_bind_param($stmtAsset, "i", $cryptoAssetId);
    mysqli_stmt_execute($stmtAsset);
    $assetResult = mysqli_stmt_get_result($stmtAsset);

    if (!$assetResult || !mysqli_fetch_assoc($assetResult)) {
        mysqli_stmt_close($stmtAsset);
        echo json_encode(["status" => "error", "msg" => "La opcion cripto seleccionada no esta disponible"]);
        exit;
    }

    mysqli_stmt_close($stmtAsset);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO cripto_wallets (usuario_id, cripto_activo_id, direccion) VALUES (?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "iis", $user_id, $cryptoAssetId, $direccion);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "ok", "tipo" => "crypto"]);
    } else {
        echo json_encode(["status" => "error", "msg" => mysqli_stmt_error($stmt)]);
    }

    mysqli_stmt_close($stmt);
    exit;
}

if ($action === "pago") {
    $plataforma = $_POST['plataforma'] ?? '';
    $enlace = trim($_POST['enlace'] ?? '');

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO pagos_online (usuario_id, plataforma, enlace) VALUES (?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $plataforma, $enlace);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "ok", "tipo" => "pago"]);
    } else {
        echo json_encode(["status" => "error", "msg" => mysqli_stmt_error($stmt)]);
    }

    mysqli_stmt_close($stmt);
    exit;
}

if ($action === "eliminar") {
    $idElemento = (int) ($_POST['id'] ?? 0);
    $tipo = $_POST['tipo'] ?? '';
    $mapa = [
        'cuenta' => 'cuentas_bancarias',
        'crypto' => 'cripto_wallets',
        'pago' => 'pagos_online',
        'servicio' => 'servicios',
    ];

    if (!isset($mapa[$tipo])) {
        echo json_encode(["status" => "error", "msg" => "Tipo no valido"]);
        exit;
    }

    $tabla = $mapa[$tipo];
    $stmt = mysqli_prepare($conn, "DELETE FROM {$tabla} WHERE id = ? AND usuario_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $idElemento, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "error", "msg" => mysqli_stmt_error($stmt)]);
    }

    mysqli_stmt_close($stmt);
    exit;
}

echo json_encode(["status" => "error", "msg" => "Accion no valida"]);
