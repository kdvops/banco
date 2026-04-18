<?php
require_once 'db.php';
require_once 'helpers.php';

if (!isset($_POST['telefono'])) {
    echo "no_encontrado";
    exit;
}

$telefono = app_normalize_phone($_POST['telefono']);

if ($telefono === '') {
    echo "no_encontrado";
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT id FROM usuarios WHERE numero = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $telefono);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    echo $user['id'];
} else {
    echo "no_encontrado";
}

mysqli_stmt_close($stmt);
