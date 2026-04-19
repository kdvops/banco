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
    echo "<meta name=\"theme-color\" content=\"#11223b\">\n";
    echo "<link rel=\"icon\" type=\"image/svg+xml\" href=\"favicon.svg\">\n";
    echo "<link rel=\"shortcut icon\" href=\"favicon.svg\">\n";

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

function app_render_site_navbar(array $options = []): void
{
    $brand = $options['brand'] ?? 'Organizador de Cobro';
    $links = $options['links'] ?? [];
    $cta = $options['cta'] ?? null;

    echo '<nav class="site-nav" aria-label="Principal">';
    echo '<div class="site-nav__inner">';
    echo '<a href="' . app_e($options['brand_href'] ?? 'index.php') . '" class="site-nav__brand">';
    echo '<span class="site-nav__brand-mark"><i class="fa-solid fa-wallet"></i></span>';
    echo '<span class="site-nav__brand-text">' . app_e($brand) . '</span>';
    echo '</a>';

    echo '<button type="button" class="site-nav__toggle js-nav-toggle" aria-expanded="false" aria-controls="site-nav-menu" aria-label="Abrir menu">';
    echo '<span></span><span></span><span></span>';
    echo '</button>';

    echo '<div class="site-nav__menu" id="site-nav-menu">';
    echo '<div class="site-nav__links">';

    foreach ($links as $link) {
        $isButton = !empty($link['button']);
        $baseClass = $isButton ? 'site-nav__button' : 'site-nav__link';
        $class = $baseClass;

        if (!$isButton && !empty($link['active'])) {
            $class .= ' is-active';
        }

        if (!empty($link['class'])) {
            $class .= ' ' . trim((string) $link['class']);
        }

        $icon = '';
        if (!empty($link['icon'])) {
            $icon = '<i class="' . app_e((string) $link['icon']) . '"></i>';
        }

        if ($isButton) {
            echo '<button type="button" class="' . app_e($class) . '"';
            if (!empty($link['aria_label'])) {
                echo ' aria-label="' . app_e((string) $link['aria_label']) . '"';
            }
            echo '>' . $icon . '<span>' . app_e($link['label'] ?? '') . '</span></button>';
            continue;
        }

        echo '<a class="' . app_e($class) . '" href="' . app_e($link['href'] ?? '#') . '">' . $icon . '<span>' . app_e($link['label'] ?? '') . '</span></a>';
    }

    echo '</div>';

    if ($cta) {
        $ctaClass = 'site-nav__cta';
        if (!empty($cta['secondary'])) {
            $ctaClass .= ' is-secondary';
        }

        echo '<a class="' . app_e($ctaClass) . '" href="' . app_e($cta['href'] ?? '#') . '">' . app_e($cta['label'] ?? '') . '</a>';
    }

    echo '</div></div></nav>';
}

function app_render_page_end(array $scripts = []): void
{
    foreach ($scripts as $src) {
        echo "\n" . '<script src="' . app_e($src) . '"></script>';
    }

    echo "\n</body>\n</html>";
}
