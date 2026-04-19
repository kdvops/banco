<?php

function app_render_profile_header(array $user, array $services, array $options = []): void
{
    $editable = (bool) ($options['editable'] ?? false);
    $shareTitle = $options['share_title'] ?? ('Perfil de ' . ($user['nombres'] ?? 'usuario'));
    $shareUrl = $options['share_url'] ?? '';
    $profileImage = app_asset_url($user['imagen'] ?? 'perfil.png', ['uploads', 'imagen'], 'uploads/perfil.png');
    $fullName = trim(($user['nombres'] ?? '') . ' ' . ($user['apellidos'] ?? ''));
    $description = trim((string) ($user['resena_personal'] ?? ''));

    echo '<header class="header">';

    echo '<button type="button" class="share-page js-share-page" title="Compartir" data-share-title="' . app_e($shareTitle) . '" data-share-url="' . app_e((string) $shareUrl) . '">';
    echo '<i class="fa-solid fa-share-nodes"></i>';
    echo '</button>';

    echo '<div class="profile-hero">';
    echo '<div class="profile-header"><div class="profile-wrapper">';
    echo '<img src="' . app_e($profileImage) . '" class="profile-main js-open-profile-modal" alt="' . app_e($user['nombres'] ?? 'Perfil') . '">';

    if ($editable) {
        echo '<button type="button" class="edit-btn js-open-modal" data-modal-target="perfil" aria-label="Editar perfil">';
        echo '<i class="fa-solid fa-pen"></i>';
        echo '</button>';
    }

    echo '</div></div>';
    echo '<div class="profile-summary">';
    echo '<p class="profile-eyebrow">Perfil para recibir dinero</p>';
    echo '<h1 class="profile-name">' . app_e($fullName !== '' ? $fullName : ($user['nombres'] ?? '')) . '</h1>';
    echo '<p class="profile-id">Cedula: ' . app_e($user['cedula'] ?? '') . '</p>';

    if ($description !== '') {
        echo '<p class="profile-description">' . app_e($description) . '</p>';
    }

    echo '</div></div>';

    echo '<section class="mini-profiles-block">';
    echo '<div class="section-heading">';
    echo '<p class="section-kicker">Accesos rapidos</p>';
    echo '<h2 class="section-title">Servicios y enlaces destacados</h2>';
    echo '</div>';
    echo '<div class="mini-profiles-scroll">';

    foreach ($services as $servicio) {
        $serviceImage = app_asset_url($servicio['imagen'] ?? '', ['uploads', 'imagen'], 'imagen/perfil.png');

        echo '<button type="button" class="mini-profile js-mini-profile"';
        echo ' data-service-id="' . (int) $servicio['id'] . '"';
        echo ' data-service-img="' . app_e($serviceImage) . '"';
        echo ' data-service-title="' . app_e($servicio['nombre_servicio'] ?? '') . '"';
        echo ' data-service-text="' . app_e($servicio['resena'] ?? '') . '"';
        echo ' data-service-link="' . app_e($servicio['enlace'] ?? '') . '"';
        echo '>';
        echo '<img src="' . app_e($serviceImage) . '" alt="' . app_e($servicio['nombre_servicio'] ?? 'Servicio') . '">';
        echo '<span>' . app_e($servicio['nombre_servicio'] ?? '') . '</span>';
        echo '</button>';
    }

    if ($editable) {
        echo '<div class="mini-profile">';
        echo '<button type="button" class="add-circle js-open-modal" data-modal-target="servicio">+</button>';
        echo '<span>Agregar</span>';
        echo '</div>';
    }

    echo '</div></section>';
    echo '</header>';
}

function app_render_profile_tabs(): void
{
    echo <<<HTML
<nav class="tab-buttons">
  <button type="button" class="tablink active js-tab-trigger" data-tab-target="Cuentas">Cuentas</button>
  <button type="button" class="tablink js-tab-trigger" data-tab-target="Criptos">Criptos</button>
  <button type="button" class="tablink js-tab-trigger" data-tab-target="Pagos">Online</button>
</nav>
HTML;
}

function app_render_accounts_section(array $accounts, bool $editable = false): void
{
    echo '<main id="Cuentas" class="tabcontent is-active"><div class="cards">';

    foreach ($accounts as $account) {
        $image = app_asset_url($account['imagen'] ?? '', ['imagen', 'uploads'], 'imagen/images.png');

        echo '<div class="card"><div class="card-top">';
        echo '<img src="' . app_e($image) . '" alt="' . app_e($account['banco'] ?? 'Banco') . '">';
        echo '<div class="card-content">';
        echo '<h3>' . app_e($account['banco'] ?? '') . '</h3>';
        echo '<p class="card-meta">' . app_e($account['tipo_cuenta'] ?? '') . '</p>';
        echo '</div><div class="card-actions">';
        echo '<button type="button" class="toggle-btn js-toggle-gate" aria-label="Mostrar detalles de cuenta"><i class="fa-regular fa-eye"></i></button>';

        if ($editable) {
            echo '<div class="card-menu">';
            echo '<button type="button" class="card-menu__trigger js-card-menu" aria-label="Opciones"><i class="fa-solid fa-ellipsis-vertical"></i></button>';
            echo '<div class="menu-dropdown">';
            echo '<button type="button" class="danger js-delete-trigger" data-delete-id="' . (int) $account['id'] . '" data-delete-type="cuenta"><i class="fa-solid fa-trash"></i> Eliminar</button>';
            echo '</div></div>';
        }

        echo '</div></div>';
        echo '<div class="gate"><div class="gate-inner"><div class="copy-stack">';
        echo '<div class="copy-row">';
        echo '<span class="copy-text">' . app_e($account['numero_cuenta'] ?? '') . '</span>';
        echo '<button type="button" class="copy-btn js-copy-btn" aria-label="Copiar cuenta"><i class="fa-regular fa-copy"></i></button>';
        echo '</div></div></div></div></div>';
    }

    if (!$accounts) {
        echo '<p>' . ($editable ? 'No tienes cuentas registradas' : 'Este usuario no tiene cuenta registrada') . '</p>';
    }

    if ($editable) {
        echo '<button type="button" class="card-add js-open-modal" data-modal-target="cuenta"><i class="fa-solid fa-building-columns"></i> Agregar Cuenta</button>';
    }

    echo '</div></main>';
}

function app_render_crypto_section(array $cryptos, bool $editable = false): void
{
    echo '<main id="Criptos" class="tabcontent"><div class="cards">';

    foreach ($cryptos as $crypto) {
        $image = app_asset_url($crypto['imagen'] ?? '', ['imagen', 'uploads'], 'imagen/images.png');
        $reference = trim((string) ($crypto['referencia'] ?? ''));
        $memoTag = trim((string) ($crypto['memo_tag'] ?? ''));

        echo '<div class="card"><div class="card-top">';
        echo '<img src="' . app_e($image) . '" alt="' . app_e($crypto['moneda'] ?? 'Cripto') . '">';
        echo '<div class="card-content"><h3>' . app_e($crypto['moneda'] ?? '') . ' (Red ' . app_e($crypto['red'] ?? '') . ')</h3>';

        if ($reference !== '') {
            echo '<p class="card-meta">Referencia: ' . app_e($reference) . '</p>';
        }

        echo '</div><div class="card-actions">';
        echo '<button type="button" class="toggle-btn js-toggle-gate" aria-label="Mostrar direccion"><i class="fa-regular fa-eye"></i></button>';

        if ($editable) {
            echo '<div class="card-menu">';
            echo '<button type="button" class="card-menu__trigger js-card-menu" aria-label="Opciones"><i class="fa-solid fa-ellipsis-vertical"></i></button>';
            echo '<div class="menu-dropdown">';
            echo '<button type="button" class="danger js-delete-trigger" data-delete-id="' . (int) $crypto['id'] . '" data-delete-type="crypto"><i class="fa-solid fa-trash"></i> Eliminar</button>';
            echo '</div></div>';
        }

        echo '</div></div>';
        echo '<div class="gate"><div class="gate-inner"><div class="copy-stack">';
        echo '<div class="copy-row">';
        echo '<span class="copy-text">' . app_e($crypto['direccion'] ?? '') . '</span>';
        echo '<button type="button" class="copy-btn js-copy-btn" aria-label="Copiar wallet"><i class="fa-regular fa-copy"></i></button>';
        echo '</div>';

        if ($memoTag !== '') {
            echo '<div class="copy-row copy-row--secondary">';
            echo '<span class="copy-label">Memo / Tag</span>';
            echo '<span class="copy-text">' . app_e($memoTag) . '</span>';
            echo '<button type="button" class="copy-btn js-copy-btn" aria-label="Copiar memo o tag"><i class="fa-regular fa-copy"></i></button>';
            echo '</div>';
        }

        echo '</div></div></div></div>';
    }

    if (!$cryptos) {
        echo '<p>' . ($editable ? 'No tienes wallets registradas' : 'Este usuario no tiene cuenta registrada') . '</p>';
    }

    if ($editable) {
        echo '<button type="button" class="card-add js-open-modal" data-modal-target="cartera"><i class="fa-brands fa-bitcoin"></i> Agregar Cartera</button>';
    }

    echo '</div></main>';
}

function app_render_payments_section(array $payments, bool $editable = false): void
{
    echo '<main id="Pagos" class="tabcontent"><div class="payment-container">';

    foreach ($payments as $payment) {
        $image = app_asset_url($payment['imagen'] ?? '', ['imagen', 'uploads'], 'imagen/images.png');
        $platform = trim((string) ($payment['plataforma'] ?? ''));
        $link = trim((string) ($payment['enlace'] ?? ''));
        $resolvedLink = app_resolve_external_url($link);
        $displayValue = $link;

        if ($displayValue === '') {
            $displayValue = 'Sin enlace configurado';
        }

        echo '<div class="card payment-card"><div class="card-top">';
        echo '<img src="' . app_e($image) . '" alt="' . app_e($platform !== '' ? $platform : 'Pago online') . '">';
        echo '<div class="card-content">';
        echo '<h3>' . app_e($platform) . '</h3>';
        echo '<p class="card-meta">Metodo para recibir pagos online</p>';
        echo '</div><div class="card-actions">';
        echo '<button type="button" class="toggle-btn js-toggle-gate" aria-label="Mostrar detalles del pago"><i class="fa-regular fa-eye"></i></button>';

        if ($resolvedLink !== '') {
            echo '<a href="' . app_e($resolvedLink) . '" class="payment-link-icon" target="_blank" rel="noopener noreferrer" aria-label="Abrir enlace en nueva pestaña"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>';
        }

        if ($editable) {
            echo '<div class="card-menu">';
            echo '<button type="button" class="card-menu__trigger js-card-menu" aria-label="Opciones"><i class="fa-solid fa-ellipsis-vertical"></i></button>';
            echo '<div class="menu-dropdown">';
            echo '<button type="button" class="danger js-delete-trigger" data-delete-id="' . (int) $payment['id'] . '" data-delete-type="pago"><i class="fa-solid fa-trash"></i> Eliminar</button>';
            echo '</div></div>';
        }

        echo '</div></div>';
        echo '<div class="gate payment-gate"><div class="gate-inner"><div class="copy-stack">';
        echo '<div class="copy-row">';
        echo '<span class="copy-text">' . app_e($displayValue) . '</span>';

        if ($link !== '') {
            echo '<button type="button" class="copy-btn js-copy-btn" aria-label="Copiar enlace o identificador"><i class="fa-regular fa-copy"></i></button>';
        }

        echo '</div>';
        echo '</div></div></div></div>';
    }

    if (!$payments) {
        echo '<p>' . ($editable ? 'No tienes metodos de pago registrados' : 'Este usuario no tiene cuenta registrada') . '</p>';
    }

    if ($editable) {
        echo '<button type="button" class="card-add js-open-modal" data-modal-target="online"><i class="fa-solid fa-wallet"></i> Agregar Cartera online</button>';
    }

    echo '</div></main>';
}

function app_render_profile_modal(array $user): void
{
    $profileImage = app_e(app_asset_url($user['imagen'] ?? 'perfil.png', ['uploads', 'imagen'], 'uploads/perfil.png'));
    $fullName = app_e(trim(($user['nombres'] ?? '') . ' ' . ($user['apellidos'] ?? '')));
    $description = app_e($user['resena_personal'] ?? 'Sin descripcion personal.');

    echo <<<HTML
<div id="profileModal" class="modal">
  <div class="modal-content">
    <button type="button" class="close js-close-modal" data-modal-target="profileModal">&times;</button>
    <img src="{$profileImage}" class="profile-modal-avatar" alt="{$fullName}">
    <h3>{$fullName}</h3>
    <p class="profile-modal-text">{$description}</p>
  </div>
</div>
HTML;
}

function app_render_mini_profile_modal(bool $editable = false): void
{
    $editableAttr = app_bool_to_attr($editable);
    $modalClass = $editable ? 'modal-content modal-perfil-ajustado' : 'modal-content';

    echo '<div id="modal" class="modal" data-editable="' . $editableAttr . '">';
    echo '<div class="' . $modalClass . '">';

    if ($editable) {
        echo '<div class="card-menu menu-extremo-izquierdo">';
        echo '<button type="button" class="card-menu__trigger js-card-menu" aria-label="Opciones"><i class="fa-solid fa-ellipsis-vertical"></i></button>';
        echo '<div class="menu-dropdown">';
        echo '<button type="button" class="danger" id="btnEliminarServicio"><i class="fa-solid fa-trash"></i> Eliminar</button>';
        echo '</div></div>';
        echo '<button type="button" class="close btn-cerrar-extremo js-close-modal" data-modal-target="modal">&times;</button>';
        echo '<img id="modalImg" class="img-modal-perfil" alt="Servicio">';
    } else {
        echo '<button type="button" class="close js-close-modal" data-modal-target="modal">&times;</button>';
        echo '<img id="modalImg" class="service-modal-image" alt="Servicio">';
    }

    echo '<h3 id="modalTitle"></h3>';
    echo '<p id="modalText"></p>';
    echo '<div id="modalLinkContainer"><a id="modalLink" target="_blank" class="btn-primary">Visitar</a></div>';
    echo '</div></div>';
}
