<?php
require_once __DIR__ . '/includes/bootstrap.php';

app_require_auth('login.php');

$userId = (int) $_SESSION['user_id'];
$user = app_fetch_user_by_id($conn, $userId);

if (!$user) {
    die('Usuario no encontrado');
}

$collections = app_fetch_profile_collections($conn, $userId);
$publicHref = !empty($user['numero']) ? 'perfildecuentas.php?numero=' . rawurlencode((string) $user['numero']) : '#';

app_render_page_start('Panel | Organizador de Metodos de Cobro', [
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
    'brand_href' => 'index.php',
    'links' => [
        ['label' => 'Mi Panel', 'href' => 'index.php', 'active' => true],
        ['label' => 'Perfil Publico', 'href' => $publicHref],
    ],
    'cta' => ['label' => 'Cerrar Sesion', 'href' => 'logout.php', 'secondary' => true],
]);

app_render_profile_header($user, $collections['servicios'], [
    'editable' => true,
    'share_title' => 'Perfil de ' . ($user['nombres'] ?? 'usuario'),
]);
app_render_profile_tabs();
app_render_accounts_section($collections['cuentas'], true);
app_render_crypto_section($collections['criptos'], true);
app_render_payments_section($collections['pagos'], true);
app_render_profile_modal($user);
app_render_mini_profile_modal(true);
app_render_dashboard_management_modals($user, $collections['pagos']);
app_render_site_footer();
app_render_page_end([
    'scripts/site-nav.js',
    'scripts/profile-ui.js',
    'scripts/dashboard.js',
]);
