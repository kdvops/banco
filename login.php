<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: index.php', true, 302);
    exit();
}

require_once __DIR__ . '/includes/bootstrap.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

app_render_page_start('Acceso | Organizador de Metodos de Cobro', [
    'styles' => [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        'styles/app-shell.css',
        'styles/auth.css',
    ],
    'body_class' => 'auth-page',
]);

app_render_auth_shell();
app_render_site_footer();
app_render_page_end([
    'scripts/auth.js',
]);
