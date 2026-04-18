<?php

function app_render_page_start(string $title, array $options = []): void
{
    $styles = $options['styles'] ?? [];
    $bodyClass = trim($options['body_class'] ?? '');
    $bodyAttributes = $options['body_attributes'] ?? [];

    echo "<!DOCTYPE html>\n";
    echo "<html lang=\"es\">\n";
    echo "<head>\n";
    echo "<meta charset=\"UTF-8\">\n";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    echo '<title>' . app_e($title) . "</title>\n";

    foreach ($styles as $href) {
        echo '<link rel="stylesheet" href="' . app_e($href) . '">' . "\n";
    }

    echo "</head>\n";

    $compiledAttributes = [];
    if ($bodyClass !== '') {
        $compiledAttributes[] = 'class="' . app_e($bodyClass) . '"';
    }

    foreach ($bodyAttributes as $name => $value) {
        $compiledAttributes[] = app_e((string) $name) . '="' . app_e((string) $value) . '"';
    }

    echo '<body ' . implode(' ', $compiledAttributes) . ">\n";
}

function app_render_site_footer(): void
{
    $year = date('Y');

    echo <<<HTML
<footer class="site-footer">
  <div class="site-footer__inner">
    <p class="site-footer__title">Organizador de Metodos de Cobro</p>
    <p class="site-footer__text">Comparte rapidamente donde pueden enviarte dinero, sin mezclar esa informacion entre chats y capturas.</p>
    <p class="site-footer__meta">&copy; {$year}. Perfil publico, cuentas y billeteras en un solo lugar.</p>
  </div>
</footer>
HTML;
}

function app_render_page_end(array $scripts = []): void
{
    foreach ($scripts as $src) {
        echo "\n" . '<script src="' . app_e($src) . '"></script>';
    }

    echo "\n</body>\n</html>";
}
