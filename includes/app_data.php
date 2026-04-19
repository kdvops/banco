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
    $sql = "SELECT b.id, b.pais_id, b.nombre, b.icono, b.activo, p.nombre AS pais_nombre, p.codigo_iso2, p.codigo_iso3
            FROM bancos b
            INNER JOIN paises p ON p.id = b.pais_id
            WHERE b.activo = 1
            ORDER BY p.nombre ASC, b.nombre ASC";

    return app_fetch_rows($conn, $sql);
}

function app_fetch_active_crypto_assets(mysqli $conn): array
{
    $sql = "SELECT id, nombre, red, icono, activo FROM cripto_activos WHERE activo = 1 ORDER BY nombre ASC, red ASC";

    return app_fetch_rows($conn, $sql);
}

function app_fetch_active_crypto_references(mysqli $conn): array
{
    $sql = "SELECT id, nombre, tipo, activo
            FROM referencias_cripto
            WHERE activo = 1
            ORDER BY nombre ASC";

    return app_fetch_rows($conn, $sql);
}

function app_fetch_active_payment_providers(mysqli $conn): array
{
    $sql = "SELECT id, nombre, icono, activo
            FROM proveedores_pago_online
            WHERE activo = 1
            ORDER BY nombre ASC";

    return app_fetch_rows($conn, $sql);
}

function app_fetch_profile_collections(mysqli $conn, int $userId): array
{
    return [
        'servicios' => app_fetch_rows($conn, "SELECT * FROM servicios WHERE usuario_id = {$userId} ORDER BY id DESC"),
        'cuentas' => app_fetch_rows(
            $conn,
            "SELECT cb.id, cb.usuario_id, cb.banco_id, cb.tipo_cuenta, cb.numero_cuenta, b.nombre AS banco, b.icono AS imagen, b.pais_id, p.nombre AS pais_nombre
             FROM cuentas_bancarias cb
             INNER JOIN bancos b ON b.id = cb.banco_id
             INNER JOIN paises p ON p.id = b.pais_id
             WHERE cb.usuario_id = {$userId}
             ORDER BY cb.id DESC"
        ),
        'criptos' => app_fetch_rows(
            $conn,
            "SELECT cw.id, cw.usuario_id, cw.cripto_activo_id, cw.referencia_cripto_id, rc.nombre AS referencia, rc.tipo AS referencia_tipo, cw.direccion, cw.memo_tag, ca.nombre AS moneda, ca.red, ca.icono AS imagen
             FROM cripto_wallets cw
             INNER JOIN cripto_activos ca ON ca.id = cw.cripto_activo_id
             LEFT JOIN referencias_cripto rc ON rc.id = cw.referencia_cripto_id
             WHERE cw.usuario_id = {$userId}
             ORDER BY cw.id DESC"
        ),
        'pagos' => app_fetch_rows(
            $conn,
            "SELECT po.id, po.usuario_id, po.proveedor_pago_online_id, po.enlace, ppo.nombre AS plataforma, ppo.icono AS imagen
             FROM pagos_online po
             INNER JOIN proveedores_pago_online ppo ON ppo.id = po.proveedor_pago_online_id
             WHERE po.usuario_id = {$userId}
             ORDER BY po.id DESC"
        ),
    ];
}
