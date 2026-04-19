<?php
require_once __DIR__ . '/includes/bootstrap.php';

$numero = app_normalize_phone($_GET['numero'] ?? '');

if ($numero === '') {
    die('Numero de telefono no proporcionado en la URL. (Ejemplo: ?numero=8497071192)');
}

$user = app_fetch_user_by_phone($conn, $numero);

if (!$user) {
    die('Usuario no encontrado');
}

$userId = (int) $user['id'];
$collections = app_fetch_profile_collections($conn, $userId);
$publicHref = !empty($user['numero']) ? 'perfildecuentas.php?numero=' . rawurlencode((string) $user['numero']) : '#';

app_render_page_start('Perfil de ' . ($user['nombres'] ?? 'usuario'), [
    'styles' => [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        'styles/style.css',
        'styles/app-shell.css',
        'styles/dashboard.css',
    ],
    'body_class' => 'app-page',
]);

app_render_site_navbar([
    'brand' => 'Organizador de Cobro',
    'brand_href' => 'login.php',
    'links' => [
        ['label' => 'Perfil Publico', 'href' => $publicHref, 'active' => true],
        ['label' => 'Acceso', 'href' => 'login.php'],
    ],
    'cta' => ['label' => 'Ir al Panel', 'href' => 'login.php'],
]);

app_render_profile_header($user, $collections['servicios'], [
    'editable' => false,
    'share_title' => 'Perfil de ' . ($user['nombres'] ?? 'usuario'),
]);
app_render_profile_tabs();
app_render_accounts_section($collections['cuentas'], false);
app_render_crypto_section($collections['criptos'], false);
app_render_payments_section($collections['pagos'], false);
app_render_profile_modal($user);
app_render_mini_profile_modal(false);
app_render_site_footer();
app_render_page_end([
    'scripts/site-nav.js',
    'scripts/profile-ui.js',
]);
