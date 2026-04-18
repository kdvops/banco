<?php

function app_e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_bool_to_attr(bool $value): string
{
    return $value ? 'true' : 'false';
}

function app_env(string $key, string $default): string
{
    $value = getenv($key);

    return ($value === false || $value === '') ? $default : $value;
}

function app_normalize_phone(?string $phone): string
{
    return preg_replace('/\D+/', '', (string) $phone) ?? '';
}

function app_asset_url(?string $value, array $folders = ['uploads', 'imagen'], string $fallback = 'imagen/perfil.png'): string
{
    $value = trim((string) $value);

    if ($value === '') {
        return $fallback;
    }

    if (preg_match('#^(?:https?:)?//#i', $value) || str_starts_with($value, 'data:')) {
        return $value;
    }

    $normalized = str_replace('\\', '/', $value);

    if (str_contains($normalized, '/')) {
        return $normalized;
    }

    foreach ($folders as $folder) {
        $candidate = __DIR__ . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $normalized;

        if (is_file($candidate)) {
            return $folder . '/' . $normalized;
        }
    }

    return $fallback;
}
