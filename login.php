<?php
require_once __DIR__ . '/includes/bootstrap.php';

app_render_page_start('Acceso | Organizador de Metodos de Cobro', [
    'styles' => [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        'styles/app-shell.css',
        'styles/auth.css',
    ],
    'body_class' => 'auth-page',
]);

app_render_site_navbar([
    'brand' => 'Organizador de Cobro',
    'brand_href' => 'login.php',
    'links' => [
        ['label' => 'Acceso', 'href' => 'login.php', 'active' => true],
        ['label' => 'Perfil Publico', 'href' => 'perfildecuentas.php?numero=8497071192'],
    ],
    'cta' => ['label' => 'Entrar', 'href' => 'login.php'],
]);

app_render_auth_shell();
app_render_site_footer();
app_render_page_end([
    'scripts/site-nav.js',
    'scripts/auth.js',
]);
