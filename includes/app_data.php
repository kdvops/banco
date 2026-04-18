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

function app_fetch_profile_collections(mysqli $conn, int $userId): array
{
    return [
        'servicios' => app_fetch_rows($conn, "SELECT * FROM servicios WHERE usuario_id = {$userId} ORDER BY id DESC"),
        'cuentas' => app_fetch_rows($conn, "SELECT * FROM cuentas_bancarias WHERE usuario_id = {$userId} ORDER BY id DESC"),
        'criptos' => app_fetch_rows($conn, "SELECT * FROM cripto_wallets WHERE usuario_id = {$userId} ORDER BY id DESC"),
        'pagos' => app_fetch_rows($conn, "SELECT * FROM pagos_online WHERE usuario_id = {$userId} ORDER BY id DESC"),
    ];
}
