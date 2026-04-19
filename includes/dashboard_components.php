<?php

function app_render_dashboard_management_modals(array $user, array $payments, array $options = []): void
{
    $banks = $options['banks'] ?? [];
    $cryptoAssets = $options['crypto_assets'] ?? [];
    $cryptoReferences = $options['crypto_references'] ?? [];
    $paymentProviders = $options['payment_providers'] ?? [];
    $publicHref = (string) ($options['public_href'] ?? '#');
    $shareTitle = (string) ($options['share_title'] ?? 'Perfil publico');
    $counts = $options['counts'] ?? [];
    $serviceCount = (int) ($counts['servicios'] ?? 0);
    $accountCount = (int) ($counts['cuentas'] ?? 0);
    $cryptoCount = (int) ($counts['criptos'] ?? 0);
    $paymentCount = (int) ($counts['pagos'] ?? count($payments));
    $totalMethods = $accountCount + $cryptoCount + $paymentCount;
    $firstName = trim((string) ($user['nombres'] ?? ''));

    echo '<div class="modal" id="perfil"><div class="modal-box"><div class="modal-header">Perfil';
    echo '<button type="button" class="close js-close-modal" data-modal-target="perfil">&times;</button></div>';
    echo '<form id="perfilForm" enctype="multipart/form-data" class="modal-body modal-form">';
    echo '<input type="hidden" name="action" value="perfil">';
    echo '<label>Foto de Perfil</label><input type="file" name="foto" class="form-control">';
    echo '<label>Nombres</label><input type="text" name="nombres" class="form-control" value="' . app_e($user['nombres'] ?? '') . '" required>';
    echo '<label>Apellidos</label><input type="text" name="apellidos" class="form-control" value="' . app_e($user['apellidos'] ?? '') . '" required>';
    echo '<label>Cedula</label><input type="text" name="cedula" class="form-control" value="' . app_e($user['cedula'] ?? '') . '">';
    echo '<label>Telefono (WhatsApp)</label><input type="text" name="numero" class="form-control" value="' . app_e($user['numero'] ?? '') . '">';
    echo '<label>Resena Personal</label><textarea name="resena_personal" class="form-control" rows="3">' . app_e($user['resena_personal'] ?? '') . '</textarea>';
    echo '<button type="submit" class="submit submit--full">Actualizar Perfil</button>';
    echo '</form></div></div>';

    echo '<div class="modal" id="servicio"><div class="modal-box"><div class="modal-header">Servicio';
    echo '<button type="button" class="close js-close-modal" data-modal-target="servicio">&times;</button></div>';
    echo '<form id="servicioForm" class="modal-body modal-form modal-form--service" enctype="multipart/form-data">';
    echo '<input type="hidden" name="action" value="servicio">';
    echo '<input type="hidden" name="servicio_id" value="">';
    echo '<div class="modal-form-intro">';
    echo '<p class="modal-form-kicker" id="servicioFormKicker">Servicio destacado</p>';
    echo '<h4 id="servicioFormTitle">Presenta mejor lo que ofreces</h4>';
    echo '<p id="servicioFormDescription">Agrega una imagen clara, una descripcion breve y un enlace directo para que puedan conocerte y contactarte rapido.</p>';
    echo '</div>';
    echo '<label>Imagen del servicio</label><input type="file" name="imagen" accept="image/*" class="service-file-input" required>';
    echo '<p class="modal-field-hint" id="servicioImageHint">Sube una imagen representativa para mostrar mejor tu servicio.</p>';
    echo '<label>Nombre del servicio</label><input type="text" name="nombre_servicio" placeholder="Servicio o especialidad" required>';
    echo '<label>Resena</label><textarea name="resena" placeholder="Descripcion del servicio"></textarea>';
    echo '<label>Enlace</label><input type="url" name="enlace" placeholder="https://">';
    echo '<button type="submit" class="submit submit--full" id="servicioSubmitBtn">Guardar</button>';
    echo '</form></div></div>';

    echo '<div class="modal" id="cuenta"><div class="modal-box"><div class="modal-header">Cuenta bancaria';
    echo '<button type="button" class="close js-close-modal" data-modal-target="cuenta">&times;</button></div>';
    echo '<form id="cuentaForm" class="modal-body modal-form">';
    echo '<input type="hidden" name="action" value="cuenta">';
    echo '<input type="hidden" name="cuenta_id" value="">';
    echo '<div class="modal-form-intro">';
    echo '<p class="modal-form-kicker" id="cuentaFormKicker">Cuenta bancaria</p>';
    echo '<h4 id="cuentaFormTitle">Selecciona primero el pais</h4>';
    echo '<p id="cuentaFormDescription">Para evitar una lista demasiado larga, elige el pais y luego veras solo las entidades financieras disponibles en ese mercado.</p>';
    echo '</div>';
    echo '<div class="bank-cascade-group">';
    echo '<label>Pais</label><select id="bankCountrySelect" name="pais_banco" class="js-bank-country-select">';
    echo '<option value="">Selecciona un pais</option>';

    $countryOptions = [];
    $defaultCountryId = '';

    foreach ($banks as $bank) {
        $countryId = (string) (int) ($bank['pais_id'] ?? 0);
        $countryName = trim((string) ($bank['pais_nombre'] ?? ''));

        if ($countryId !== '' && $countryId !== '0' && $countryName !== '' && !isset($countryOptions[$countryId])) {
            $countryOptions[$countryId] = $countryName;

            if (($bank['codigo_iso2'] ?? '') === 'DO') {
                $defaultCountryId = $countryId;
            }
        }
    }

    foreach ($countryOptions as $countryId => $countryName) {
        $selectedAttr = $countryId === $defaultCountryId ? ' selected' : '';
        echo '<option value="' . app_e($countryId) . '"' . $selectedAttr . '>' . app_e($countryName) . '</option>';
    }

    echo '</select>';
    echo '<p class="modal-field-hint">Sugerencia: dejamos Republica Dominicana preseleccionada para acelerar el registro si cobras localmente.</p>';
    echo '<label>Banco o entidad</label><select id="bankEntitySelect" name="banco_id" class="js-bank-entity-select" required disabled data-placeholder="Selecciona un banco">';
    echo '<option value="">Selecciona un pais primero</option>';

    foreach ($banks as $bank) {
        echo '<option value="' . (int) ($bank['id'] ?? 0) . '" data-country-id="' . (int) ($bank['pais_id'] ?? 0) . '" hidden disabled>';
        echo app_e($bank['nombre'] ?? '');
        echo '</option>';
    }

    echo '</select>';
    echo '</div>';
    echo '<label>Tipo de cuenta</label><select name="tipo"><option>Ahorro</option><option>Corriente</option></select>';
    echo '<label>Numero de cuenta</label><input type="text" name="numero" placeholder="Numero de cuenta" required>';
    echo '<button type="submit" class="submit submit--full" id="cuentaSubmitBtn">Guardar</button>';
    echo '</form></div></div>';

    echo '<div class="modal" id="cartera"><div class="modal-box"><div class="modal-header">Cartera cripto';
    echo '<button type="button" class="close js-close-modal" data-modal-target="cartera">&times;</button></div>';
    echo '<form id="cryptoForm" class="modal-body modal-form">';
    echo '<input type="hidden" name="action" value="crypto">';
    echo '<div class="modal-form-intro">';
    echo '<p class="modal-form-kicker">Cartera cripto</p>';
    echo '<h4>Comparte una wallet completa y sin ambiguedades</h4>';
    echo '<p>Ademas de la direccion, algunas monedas o exchanges pueden requerir memo, tag o referencia adicional para acreditar el deposito correctamente.</p>';
    echo '</div>';
    echo '<label>Criptomoneda</label><select name="cripto_activo_id" required>';
    echo '<option value="">Selecciona una opcion</option>';

    foreach ($cryptoAssets as $asset) {
        $optionLabel = trim((string) ($asset['nombre'] ?? '')) . ' (' . trim((string) ($asset['red'] ?? '')) . ')';
        echo '<option value="' . (int) ($asset['id'] ?? 0) . '">' . app_e($optionLabel) . '</option>';
    }

    echo '</select>';
    echo '<label>Referencia de cartera o exchange</label><select name="referencia_cripto_id">';
    echo '<option value="">Selecciona una opcion</option>';

    foreach ($cryptoReferences as $reference) {
        $referenceLabel = trim((string) ($reference['nombre'] ?? ''));
        $referenceType = trim((string) ($reference['tipo'] ?? ''));

        if ($referenceType !== '') {
            $referenceLabel .= ' (' . str_replace('_', ' ', $referenceType) . ')';
        }

        echo '<option value="' . (int) ($reference['id'] ?? 0) . '">' . app_e($referenceLabel) . '</option>';
    }

    echo '</select>';
    echo '<label>Direccion</label><input type="text" name="direccion" placeholder="Direccion de la cartera" required>';
    echo '<label>Memo / Tag / Payment ID</label><input type="text" name="memo_tag" placeholder="Opcional, solo si tu wallet lo requiere">';
    echo '<p class="modal-field-hint">Usa este campo para redes o plataformas que exigen un identificador adicional, como XRP u otras integraciones custodiales.</p>';
    echo '<button type="submit" class="submit submit--full">Guardar</button>';
    echo '</form></div></div>';

    echo '<div class="modal" id="online"><div class="modal-box"><div class="modal-header">Pago online';
    echo '<button type="button" class="close js-close-modal" data-modal-target="online">&times;</button></div>';
    echo '<div class="modal-body"><div id="listaPagosOnline" class="payments-current-list">';
    echo '<p class="payments-current-title">Metodos actuales:</p>';

    if ($payments) {
        foreach ($payments as $payment) {
            echo '<div class="payment-pill">';
            echo '<span class="payment-pill__name">' . app_e($payment['plataforma'] ?? '') . '</span>';
            echo '<button type="button" class="card-menu__trigger payment-pill__remove js-delete-trigger" data-delete-id="' . (int) $payment['id'] . '" data-delete-type="pago" aria-label="Eliminar metodo"><i class="fa-solid fa-xmark"></i></button>';
            echo '</div>';
        }
    } else {
        echo '<p class="payments-empty-note">No hay metodos guardados.</p>';
    }

    echo '</div><form id="plataformaForm" class="modal-form">';
    echo '<input type="hidden" name="action" value="pago">';
    echo '<label>Proveedor</label><select name="proveedor_pago_online_id" required>';
    echo '<option value="">Selecciona una opcion</option>';

    foreach ($paymentProviders as $provider) {
        echo '<option value="' . (int) ($provider['id'] ?? 0) . '">' . app_e($provider['nombre'] ?? '') . '</option>';
    }

    echo '</select>';
    echo '<label>Enlace</label><input type="url" name="enlace" placeholder="https://">';
    echo '<button type="submit" class="submit submit--full">Guardar Nuevo</button>';
    echo '</form></div></div></div>';

    echo '<button type="button" class="search-float-btn js-open-modal" data-modal-target="searchModal"><i class="fa-solid fa-magnifying-glass"></i></button>';
    echo '<div class="modal" id="searchModal"><div class="modal-box"><div class="modal-header">Buscar por telefono';
    echo '<button type="button" class="close js-close-modal" data-modal-target="searchModal">&times;</button></div>';
    echo '<form id="searchForm" class="modal-body modal-form">';
    echo '<label>Numero telefonico</label><input type="tel" id="telefonoBusqueda" placeholder="Ej: 8091234567" required>';
    echo '<button type="submit" class="submit">Buscar</button><div id="searchAlert" class="search-alert"></div>';
    echo '</form></div></div>';

    echo '<div id="settingsModal" class="settings-overlay"><div class="settings-box">';
    echo '<div class="settings-header"><span>Centro de perfil</span>';
    echo '<button type="button" class="cerrar-x js-settings-close" aria-label="Cerrar centro de perfil">&times;</button></div>';
    echo '<div class="settings-body settings-body--rich">';
    echo '<section class="settings-panel settings-panel--hero">';
    echo '<p class="settings-kicker">Organiza y comparte mejor</p>';
    echo '<h3>' . app_e($firstName !== '' ? $firstName . ', optimiza tu perfil de cobro' : 'Optimiza tu perfil de cobro') . '</h3>';
    echo '<p>Usa este espacio para compartir tu perfil publico, completar metodos de cobro y dar mas confianza a quien te va a transferir dinero.</p>';
    echo '</section>';

    echo '<section class="settings-stats" aria-label="Resumen del perfil">';
    echo '<div class="settings-stat"><strong>' . $serviceCount . '</strong><span>Servicios</span></div>';
    echo '<div class="settings-stat"><strong>' . $totalMethods . '</strong><span>Metodos</span></div>';
    echo '<div class="settings-stat"><strong>' . $paymentCount . '</strong><span>Online</span></div>';
    echo '</section>';

    echo '<section class="settings-group">';
    echo '<h4>Acciones rapidas</h4>';
    echo '<div class="settings-actions">';
    echo '<button type="button" class="settings-action settings-action--primary js-share-page js-settings-dismiss" data-share-title="' . app_e($shareTitle) . '" data-share-url="' . app_e($publicHref) . '"><i class="fa-solid fa-share-nodes"></i><span>Compartir perfil</span></button>';
    echo '<button type="button" class="settings-action js-copy-url js-settings-dismiss" data-copy-url="' . app_e($publicHref) . '"><i class="fa-regular fa-copy"></i><span>Copiar enlace publico</span></button>';
    echo '<a href="' . app_e($publicHref) . '" class="settings-action settings-action--link" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-arrow-up-right-from-square"></i><span>Ver perfil publico</span></a>';
    echo '<button type="button" class="settings-action js-settings-open-modal" data-modal-target="perfil"><i class="fa-solid fa-user-pen"></i><span>Editar perfil</span></button>';
    echo '<button type="button" class="settings-action js-settings-open-modal" data-modal-target="servicio"><i class="fa-solid fa-bolt"></i><span>Agregar acceso rapido</span></button>';
    echo '<button type="button" class="settings-action js-settings-open-modal" data-modal-target="cuenta"><i class="fa-solid fa-building-columns"></i><span>Agregar cuenta bancaria</span></button>';
    echo '<button type="button" class="settings-action js-settings-open-modal" data-modal-target="cartera"><i class="fa-brands fa-bitcoin"></i><span>Agregar cartera cripto</span></button>';
    echo '<button type="button" class="settings-action js-settings-open-modal" data-modal-target="online"><i class="fa-solid fa-wallet"></i><span>Agregar cobro online</span></button>';
    echo '<button type="button" class="settings-action js-settings-open-modal" data-modal-target="searchModal"><i class="fa-solid fa-magnifying-glass"></i><span>Buscar por telefono</span></button>';
    echo '</div>';
    echo '</section>';

    echo '<section class="settings-group">';
    echo '<h4>Recomendaciones para cobrar mejor</h4>';
    echo '<div class="settings-tips">';
    echo '<article class="settings-tip"><i class="fa-solid fa-layer-group"></i><div><strong>Ofrece mas de un metodo</strong><p>Combina banco, wallet y opcion online para reducir friccion cuando alguien quiera enviarte dinero.</p></div></article>';
    echo '<article class="settings-tip"><i class="fa-solid fa-badge-check"></i><div><strong>Completa tu perfil publico</strong><p>Foto, nombre y descripcion clara aumentan confianza y hacen mas facil identificarte.</p></div></article>';
    echo '<article class="settings-tip"><i class="fa-solid fa-bullhorn"></i><div><strong>Comparte un solo enlace</strong><p>En vez de reenviar cuentas por chat, usa tu perfil publico para mantener todo actualizado en un solo lugar.</p></div></article>';
    echo '</div>';
    echo '</section>';

    echo '<section class="settings-group">';
    echo '<h4>Mensaje de confianza</h4>';
    echo '<p class="settings-note">Esta app organiza datos para recibir dinero, pero no procesa pagos. Mientras mas claro y ordenado este tu perfil, mas rapido podran transferirte sin errores.</p>';
    echo '</section>';

    echo '<a href="logout.php" class="settings-link"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</a>';
    echo '</div></div></div>';

    echo '<div class="modal" id="confirmModal"><div class="modal-box"><div class="modal-header">Confirmar accion';
    echo '<button type="button" class="close js-close-modal" data-modal-target="confirmModal">&times;</button></div>';
    echo '<div class="modal-body"><p id="confirmText"></p>';
    echo '<button type="button" id="confirmDeleteBtn">Confirmar</button>';
    echo '<button type="button" class="js-close-modal" data-modal-target="confirmModal">Cancelar</button>';
    echo '</div></div></div>';
}
