<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status"=>"error","msg"=>"No autorizado"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

/* ================= PERFIL ================= */

/* ================= PERFIL (Corregido) ================= */
if ($action === "perfil") {
    $nombres   = $_POST['nombres'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $cedula    = $_POST['cedula'] ?? '';
    $numero    = $_POST['numero'] ?? ''; // Coincide con la columna 'numero' en SQL
    $resena    = $_POST['resena_personal'] ?? '';

    // 1. Obtener imagen actual por si no se sube una nueva (La columna se llama 'imagen')
    $sql_current = "SELECT imagen FROM usuarios WHERE id = ?";
    $stmt_curr = mysqli_prepare($conn, $sql_current);
    mysqli_stmt_bind_param($stmt_curr, "i", $user_id);
    mysqli_stmt_execute($stmt_curr);
    $res_curr = mysqli_stmt_get_result($stmt_curr);
    $user_data = mysqli_fetch_assoc($res_curr);
    $foto_actual = $user_data['imagen'] ?? 'perfil.png';

    // 2. Manejo de la subida de archivo
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = time() . "_" . $user_id . "." . $ext;
        $ruta_destino = "uploads/" . $nombre_archivo;
        
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        
        if(move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)){
            $foto_actual = $nombre_archivo;
        }
    }

    // 3. Actualizar la base de datos (Nombres de columnas exactos del SQL)
    $sql_update = "UPDATE usuarios SET nombres=?, apellidos=?, cedula=?, numero=?, resena_personal=?, imagen=? WHERE id=?";
    $stmt_upd = mysqli_prepare($conn, $sql_update);
    
    // "ssssssi" -> 6 strings y 1 integer
    mysqli_stmt_bind_param($stmt_upd, "ssssssi", 
        $nombres, 
        $apellidos, 
        $cedula, 
        $numero, 
        $resena, 
        $foto_actual, 
        $user_id
    );

    if(mysqli_stmt_execute($stmt_upd)){
        echo json_encode(["status"=>"ok", "msg"=>"Perfil actualizado correctamente"]);
    } else {
        echo json_encode(["status"=>"error", "msg"=>"Error al actualizar: " . mysqli_error($conn)]);
    }
    mysqli_stmt_close($stmt_upd);
    exit();
}

// ... Resto de las acciones (servicio, cuenta, crypto, pago) ...


/* ================= SERVICIO ================= */
/* ================= SERVICIO (Corregido) ================= */
if ($action === "servicio") {
    $nombre = $_POST['nombre_servicio'];
    $resena = $_POST['resena'];
    $link = $_POST['link']; // Este viene del name="link" del HTML

    $imagen = null;
    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
        $ruta = "uploads/" . time() . "_" . $_FILES['imagen']['name'];
        if (!is_dir('uploads')) mkdir('uploads', 0777, true); // Asegura que la carpeta existe
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
        $imagen = $ruta;
    }

    // CAMBIO AQUÍ: Se cambió 'link' por 'enlace'
    $sql = "INSERT INTO servicios (usuario_id, nombre_servicio, resena, enlace, imagen) VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $nombre, $resena, $link, $imagen);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status"=>"ok","tipo"=>"servicio"]);
    } else {
        echo json_encode(["status"=>"error","msg"=>mysqli_error($conn)]);
    }
}

/* ================= CUENTA ================= */
if ($action === "cuenta") {
    $banco = $_POST['banco'];
    $tipo = $_POST['tipo'];
    $numero = $_POST['numero'];
    // Imagen por defecto o lógica para subir imagen de banco
    $img_default = strtolower($banco) . ".png"; 

    $sql = "INSERT INTO cuentas_bancarias (usuario_id, banco, tipo_cuenta, numero_cuenta, imagen) VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $banco, $tipo, $numero, $img_default);
    mysqli_stmt_execute($stmt);

    echo json_encode(["status"=>"ok","tipo"=>"cuenta"]);
}

/* ================= CRYPTO ================= */
if ($action === "crypto") {

    $moneda = $_POST['moneda'];
    $red = $_POST['red'];
    $direccion = $_POST['direccion'];

    // Imagen automática según moneda
    $img_default2 = strtolower($moneda) . ".png";

    $sql = "INSERT INTO cripto_wallets (usuario_id, moneda, red, direccion, imagen) VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "issss", $user_id, $moneda, $red, $direccion, $img_default2);

    mysqli_stmt_execute($stmt);

    echo json_encode(["status"=>"ok","tipo"=>"crypto"]);
}

/* ================= PAGOS ================= */
/* ================= PAGOS (Corregido) ================= */
if ($action === "pago") {
    $plataforma = $_POST['plataforma'];
    $link = $_POST['link']; // Viene del name="link" en el HTML

    // Se cambia la columna 'link' por 'enlace' que es la que existe en SQL
    $sql = "INSERT INTO pagos_online (usuario_id, plataforma, enlace) VALUES (?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Cambiamos la cadena de tipos a "iss" (integer, string, string)
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $plataforma, $link);
    
    if(mysqli_stmt_execute($stmt)) {
        echo json_encode(["status"=>"ok","tipo"=>"pago"]);
    } else {
        echo json_encode(["status"=>"error","msg"=>mysqli_error($conn)]);
    }
}





/* ================= ELIMINAR ELEMENTO ================= */
if ($action === "eliminar") {
    $id_elemento = $_POST['id'] ?? 0;
    $tipo = $_POST['tipo'] ?? '';
    
    // Definir la tabla según el tipo enviado
    $tabla = "";
    switch ($tipo) {
        case 'cuenta': $tabla = "cuentas_bancarias"; break;
        case 'crypto': $tabla = "cripto_wallets"; break;
        case 'pago':   $tabla = "pagos_online"; break;
        case 'servicio': $tabla = "servicios"; break;
    }

    if (!empty($tabla)) {
        // Validamos que el elemento pertenezca al usuario de la sesión por seguridad
        $sql = "DELETE FROM $tabla WHERE id = ? AND usuario_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id_elemento, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "msg" => mysqli_error($conn)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["status" => "error", "msg" => "Tipo no válido"]);
    }
    exit();
}