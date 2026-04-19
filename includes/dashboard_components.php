<?php

function app_render_dashboard_management_modals(array $user, array $payments): void
{
    echo '<div class="modal" id="perfil"><div class="modal-box"><div class="modal-header">Perfil';
    echo '<button type="button" class="close js-close-modal" data-modal-target="perfil">&times;</button></div>';
    echo '<form id="perfilForm" enctype="multipart/form-data" class="modal-body">';
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
    echo '<form id="servicioForm" class="modal-body" enctype="multipart/form-data">';
    echo '<input type="hidden" name="action" value="servicio">';
    echo '<label>Imagen del servicio</label><input type="file" name="imagen" accept="image/*" required>';
    echo '<label>Nombre del servicio</label><input type="text" name="nombre_servicio" placeholder="Servicio o especialidad" required>';
    echo '<label>Resena</label><textarea name="resena" placeholder="Descripcion del servicio"></textarea>';
    echo '<label>Enlace</label><input type="url" name="enlace" placeholder="https://">';
    echo '<button type="submit" class="submit">Guardar</button>';
    echo '</form></div></div>';

    echo '<div class="modal" id="cuenta"><div class="modal-box"><div class="modal-header">Cuenta bancaria';
    echo '<button type="button" class="close js-close-modal" data-modal-target="cuenta">&times;</button></div>';
    echo '<form id="cuentaForm" class="modal-body">';
    echo '<input type="hidden" name="action" value="cuenta">';
    echo '<label>Banco</label><select name="banco" required><option>BHD</option><option>Ademi</option><option>Ban Reservas</option><option>Santa Cruz</option></select>';
    echo '<label>Tipo de cuenta</label><select name="tipo"><option>Ahorro</option><option>Corriente</option></select>';
    echo '<label>Numero de cuenta</label><input type="text" name="numero" placeholder="Numero de cuenta" required>';
    echo '<button type="submit" class="submit">Guardar</button>';
    echo '</form></div></div>';

    echo '<div class="modal" id="cartera"><div class="modal-box"><div class="modal-header">Cartera cripto';
    echo '<button type="button" class="close js-close-modal" data-modal-target="cartera">&times;</button></div>';
    echo '<form id="cryptoForm" class="modal-body">';
    echo '<input type="hidden" name="action" value="crypto">';
    echo '<label>Criptomoneda</label><select name="moneda"><option>BTC</option><option>ETHER</option></select>';
    echo '<label>Red</label><select name="red"><option value="BTC">BTC</option><option value="ERC20">ERC20</option><option value="TRC20">TRC20</option></select>';
    echo '<label>Direccion</label><input type="text" name="direccion" placeholder="Direccion de la cartera" required>';
    echo '<button type="submit" class="submit">Guardar</button>';
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

    echo '</div><form id="plataformaForm">';
    echo '<input type="hidden" name="action" value="pago">';
    echo '<label>Plataforma</label><select name="plataforma"><option value="Zelle">Zelle</option><option value="PayPal">PayPal</option></select>';
    echo '<label>Enlace</label><input type="url" name="enlace" placeholder="https://">';
    echo '<button type="submit" class="submit submit--full">Guardar Nuevo</button>';
    echo '</form></div></div></div>';

    echo '<button type="button" class="search-float-btn js-open-modal" data-modal-target="searchModal"><i class="fa-solid fa-magnifying-glass"></i></button>';
    echo '<div class="modal" id="searchModal"><div class="modal-box"><div class="modal-header">Buscar por telefono';
    echo '<button type="button" class="close js-close-modal" data-modal-target="searchModal">&times;</button></div>';
    echo '<form id="searchForm" class="modal-body">';
    echo '<label>Numero telefonico</label><input type="tel" id="telefonoBusqueda" placeholder="Ej: 8091234567" required>';
    echo '<button type="submit" class="submit">Buscar</button><div id="searchAlert" class="search-alert"></div>';
    echo '</form></div></div>';

    echo '<div id="settingsModal" class="settings-overlay"><div class="settings-box"><div class="settings-header"><span>Configuracion</span>';
    echo '<button type="button" class="cerrar-x js-settings-close">&times;</button></div><div class="settings-body">';
    echo '<p>La configuracion visual sigue disponible. La persistencia en base de datos no estaba implementada en la app original.</p>';
    echo '<a href="logout.php" class="settings-link"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</a>';
    echo '</div></div></div>';

    echo '<div class="modal" id="confirmModal"><div class="modal-box"><div class="modal-header">Confirmar accion';
    echo '<button type="button" class="close js-close-modal" data-modal-target="confirmModal">&times;</button></div>';
    echo '<div class="modal-body"><p id="confirmText"></p>';
    echo '<button type="button" id="confirmDeleteBtn">Confirmar</button>';
    echo '<button type="button" class="js-close-modal" data-modal-target="confirmModal">Cancelar</button>';
    echo '</div></div></div>';
}
