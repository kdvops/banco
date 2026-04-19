<?php

function app_require_auth(string $redirectTo = 'login.php'): void
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: {$redirectTo}");
        exit();
    }
}

function app_fetch_rows(mysqli $conn, string $sql): array
{
    $result = mysqli_query($conn, $sql);

    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

function app_fetch_user_by_id(mysqli $conn, int $userId): ?array
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM usuarios WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    return $user ?: null;
}

function app_fetch_user_by_phone(mysqli $conn, string $phone): ?array
{
    $normalizedPhone = app_normalize_phone($phone);

    if ($normalizedPhone === '') {
        return null;
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM usuarios WHERE numero = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $normalizedPhone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    return $user ?: null;
}

function app_fetch_active_banks(mysqli $conn): array
{
    $sql = "SELECT id, nombre, icono, activo FROM bancos WHERE activo = 1 ORDER BY nombre ASC";

    return app_fetch_rows($conn, $sql);
}

function app_fetch_active_crypto_assets(mysqli $conn): array
{
    $sql = "SELECT id, nombre, red, icono, activo FROM cripto_activos WHERE activo = 1 ORDER BY nombre ASC, red ASC";

    return app_fetch_rows($conn, $sql);
}

function app_fetch_profile_collections(mysqli $conn, int $userId): array
{
    return [
        'servicios' => app_fetch_rows($conn, "SELECT * FROM servicios WHERE usuario_id = {$userId} ORDER BY id DESC"),
        'cuentas' => app_fetch_rows(
            $conn,
            "SELECT cb.id, cb.usuario_id, cb.banco_id, cb.tipo_cuenta, cb.numero_cuenta, b.nombre AS banco, b.icono AS imagen
             FROM cuentas_bancarias cb
             INNER JOIN bancos b ON b.id = cb.banco_id
             WHERE cb.usuario_id = {$userId}
             ORDER BY cb.id DESC"
        ),
        'criptos' => app_fetch_rows(
            $conn,
            "SELECT cw.id, cw.usuario_id, cw.cripto_activo_id, cw.direccion, ca.nombre AS moneda, ca.red, ca.icono AS imagen
             FROM cripto_wallets cw
             INNER JOIN cripto_activos ca ON ca.id = cw.cripto_activo_id
             WHERE cw.usuario_id = {$userId}
             ORDER BY cw.id DESC"
        ),
        'pagos' => app_fetch_rows($conn, "SELECT * FROM pagos_online WHERE usuario_id = {$userId} ORDER BY id DESC"),
    ];
}
