<?php
session_start();

require_once 'db.php';
require_once 'helpers.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$stmtUser = mysqli_prepare($conn, "SELECT * FROM usuarios WHERE id = ?");
mysqli_stmt_bind_param($stmtUser, "i", $user_id);
mysqli_stmt_execute($stmtUser);
$resultUser = mysqli_stmt_get_result($stmtUser);
$user = $resultUser ? mysqli_fetch_assoc($resultUser) : null;
mysqli_stmt_close($stmtUser);

if (!$user) {
    die("Usuario no encontrado");
}

function fetchRows(mysqli $conn, string $sql): array
{
    $result = mysqli_query($conn, $sql);

    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

$servicios = fetchRows($conn, "SELECT * FROM servicios WHERE usuario_id = {$user_id} ORDER BY id DESC");
$cuentas = fetchRows($conn, "SELECT * FROM cuentas_bancarias WHERE usuario_id = {$user_id} ORDER BY id DESC");
$criptos = fetchRows($conn, "SELECT * FROM cripto_wallets WHERE usuario_id = {$user_id} ORDER BY id DESC");
$pagos = fetchRows($conn, "SELECT * FROM pagos_online WHERE usuario_id = {$user_id} ORDER BY id DESC");

$profileImage = app_asset_url($user['imagen'] ?? 'perfil.png', ['uploads', 'imagen'], 'uploads/perfil.png');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tarjetas con Perfiles - Panel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="styles/style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    .btn-ajustes-fijo {
      position: fixed;
      top: 20px;
      left: 20px;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: #444;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 9999;
      box-shadow: 0 2px 10px rgba(0, 0, 0, .2);
    }

    .settings-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .6);
      z-index: 10000;
      align-items: center;
      justify-content: center;
    }

    .settings-box {
      width: 90%;
      max-width: 340px;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
    }

    .settings-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px;
      background: #f8f9fa;
      border-bottom: 1px solid #ddd;
      font-weight: 700;
    }

    .settings-body {
      padding: 15px;
    }

    .settings-body p {
      margin-top: 0;
      color: #666;
      font-size: 14px;
    }

    .settings-link {
      display: block;
      text-align: center;
      padding: 10px;
      color: #d9534f;
      text-decoration: none;
      font-weight: 700;
    }

    .modal-perfil-ajustado {
      position: relative !important;
      padding-top: 65px !important;
      text-align: center;
      overflow: visible !important;
    }

    .menu-extremo-izquierdo {
      position: absolute !important;
      top: 15px !important;
      left: 15px !important;
      z-index: 10;
    }

    .btn-cerrar-extremo {
      position: absolute !important;
      top: 10px !important;
      right: 15px !important;
      font-size: 30px !important;
      cursor: pointer !important;
      z-index: 10;
    }

    .img-modal-perfil {
      width: 90px !important;
      height: 90px !important;
      border-radius: 50% !important;
      object-fit: cover !important;
      display: block;
      margin: 0 auto 15px;
      border: 3px solid #eee;
    }
  </style>
</head>
<body>

<header class="header">
  <div class="btn-ajustes-fijo" onclick="openSettingsModal()">
    <i class="fas fa-cog"></i>
  </div>

  <div class="share-page" onclick="sharePage()" title="Compartir">
    <i class="fa-solid fa-share-nodes"></i>
  </div>

  <div class="profile-header">
    <div class="profile-wrapper">
      <img
        src="<?php echo htmlspecialchars($profileImage); ?>"
        class="profile-main"
        onclick="openProfileModal()"
        alt="<?php echo htmlspecialchars($user['nombres']); ?>"
      />
      <button class="edit-btn" onclick="openModal('perfil')">
        <i class="fa-solid fa-pen"></i>
      </button>
    </div>
  </div>

  <p><strong><?php echo htmlspecialchars($user['nombres']); ?></strong></p>
  <p style="color:#666;">Cedula: <?php echo htmlspecialchars($user['cedula']); ?></p>

  <div class="mini-profiles-scroll">
    <?php foreach ($servicios as $servicio) : ?>
      <div
        class="mini-profile"
        data-link="<?php echo htmlspecialchars($servicio['enlace'] ?? ''); ?>"
        onclick="openMiniProfile(
          this,
          <?php echo (int) $servicio['id']; ?>,
          <?php echo json_encode(app_asset_url($servicio['imagen'] ?? '', ['uploads', 'imagen'], 'imagen/perfil.png'), JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
          <?php echo json_encode($servicio['nombre_servicio'] ?? '', JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
          <?php echo json_encode($servicio['resena'] ?? '', JSON_HEX_APOS | JSON_HEX_QUOT); ?>
        )"
      >
        <img src="<?php echo htmlspecialchars(app_asset_url($servicio['imagen'] ?? '', ['uploads', 'imagen'], 'imagen/perfil.png')); ?>">
        <span><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></span>
      </div>
    <?php endforeach; ?>

    <div class="mini-profile">
      <div class="add-circle" onclick="openModal('servicio')">+</div>
      <span>Agregar</span>
    </div>
  </div>
</header>

<nav class="tab-buttons">
  <button class="tablink active" onclick="openTab(event, 'Cuentas')">Cuentas</button>
  <button class="tablink" onclick="openTab(event, 'Criptos')">Criptos</button>
  <button class="tablink" onclick="openTab(event, 'Pagos')">Online</button>
</nav>

<main id="Cuentas" class="tabcontent" style="display:block;">
  <div class="cards">
    <?php foreach ($cuentas as $cuenta) : ?>
      <div class="card">
        <div class="card-top">
          <img src="<?php echo htmlspecialchars(app_asset_url($cuenta['imagen'] ?? '', ['imagen', 'uploads'], 'imagen/images.png')); ?>">

          <div class="card-content">
            <h3><?php echo htmlspecialchars($cuenta['banco']); ?></h3>
            <div class="copy-row">
              <?php echo htmlspecialchars($cuenta['tipo_cuenta']); ?> -
              <span class="copy-text"><?php echo htmlspecialchars($cuenta['numero_cuenta']); ?></span>
              <button class="copy-btn" onclick="copyAuto(this)">
                <i class="fa-regular fa-copy"></i>
              </button>
            </div>
          </div>

          <div class="card-menu">
            <i class="fa-solid fa-ellipsis-vertical" onclick="toggleCardMenu(this)"></i>
            <div class="menu-dropdown">
              <button class="danger" onclick="prepararEliminar(<?php echo (int) $cuenta['id']; ?>, 'cuenta')">
                <i class="fa-solid fa-trash"></i> Eliminar
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (count($cuentas) === 0) : ?>
      <p>No tienes cuentas registradas</p>
    <?php endif; ?>

    <div class="card-add" onclick="openModal('cuenta')">
      <i class="fa-solid fa-building-columns"></i> Agregar Cuenta
    </div>
  </div>
</main>

<main id="Criptos" class="tabcontent">
  <div class="cards">
    <?php foreach ($criptos as $crypto) : ?>
      <div class="card">
        <div class="card-top">
          <img src="<?php echo htmlspecialchars(app_asset_url($crypto['imagen'] ?? '', ['imagen', 'uploads'], 'imagen/images.png')); ?>">

          <div class="card-content">
            <h3><?php echo htmlspecialchars($crypto['moneda']); ?> (Red <?php echo htmlspecialchars($crypto['red']); ?>)</h3>
          </div>

          <div class="toggle-btn" onclick="toggleGate(this)">
            <i class="fa-regular fa-eye"></i>
          </div>

          <div class="card-menu">
            <i class="fa-solid fa-ellipsis-vertical" onclick="toggleCardMenu(this)"></i>
            <div class="menu-dropdown">
              <button class="danger" onclick="prepararEliminar(<?php echo (int) $crypto['id']; ?>, 'crypto')">
                <i class="fa-solid fa-trash"></i> Eliminar
              </button>
            </div>
          </div>
        </div>

        <div class="gate">
          <div class="gate-inner">
            <div class="copy-row">
              <span class="copy-text"><?php echo htmlspecialchars($crypto['direccion']); ?></span>
              <button class="copy-btn" onclick="copyAuto(this)">
                <i class="fa-regular fa-copy"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (count($criptos) === 0) : ?>
      <p>No tienes wallets registradas</p>
    <?php endif; ?>

    <div class="card-add" onclick="openModal('cartera')">
      <i class="fa-brands fa-bitcoin"></i> Agregar Cartera
    </div>
  </div>
</main>

<main id="Pagos" class="tabcontent">
  <div class="payment-container">
    <?php foreach ($pagos as $pago) : ?>
      <?php $clase = strtolower(str_replace(' ', '', $pago['plataforma'])); ?>
      <button
        class="pay-btn <?php echo htmlspecialchars($clase); ?>"
        onclick='window.open(<?php echo json_encode($pago["enlace"]); ?>, "_blank")'
      >
        <?php echo htmlspecialchars($pago['plataforma']); ?>
      </button>
    <?php endforeach; ?>

    <?php if (count($pagos) === 0) : ?>
      <p>No tienes metodos de pago registrados</p>
    <?php endif; ?>

    <div class="card-add" onclick="openModal('online')">
      <i class="fa-brands fa-wallet"></i> Agregar Cartera online
    </div>
  </div>
</main>

<div id="profileModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeProfileModal()">&times;</span>
    <img
      src="<?php echo htmlspecialchars($profileImage); ?>"
      width="100"
      style="border-radius:50%; object-fit:cover; aspect-ratio:1/1;"
    >
    <h3><?php echo htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']); ?></h3>
    <p style="color:#666;"><?php echo htmlspecialchars($user['resena_personal']); ?></p>
  </div>
</div>

<div id="modal" class="modal">
  <div class="modal-content modal-perfil-ajustado">
    <div class="card-menu menu-extremo-izquierdo">
      <i class="fa-solid fa-ellipsis-vertical" onclick="toggleCardMenu(this)"></i>
      <div class="menu-dropdown">
        <button class="danger" id="btnEliminarServicio">
          <i class="fa-solid fa-trash"></i> Eliminar
        </button>
      </div>
    </div>

    <span class="close btn-cerrar-extremo" onclick="closeModal('modal')">&times;</span>

    <img id="modalImg" class="img-modal-perfil">
    <h3 id="modalTitle"></h3>
    <p id="modalText"></p>

    <div id="modalLinkContainer">
      <a id="modalLink" target="_blank" class="btn-primary">Visitar</a>
    </div>
  </div>
</div>

<div class="modal" id="perfil">
  <div class="modal-box">
    <div class="modal-header">
      Perfil
      <span class="close" onclick="closeModal('perfil')">&times;</span>
    </div>

    <form id="perfilForm" enctype="multipart/form-data" class="modal-body">
      <input type="hidden" name="action" value="perfil">

      <label>Foto de Perfil</label>
      <input type="file" name="foto" class="form-control">

      <label>Nombres</label>
      <input type="text" name="nombres" class="form-control" value="<?php echo htmlspecialchars($user['nombres']); ?>" required>

      <label>Apellidos</label>
      <input type="text" name="apellidos" class="form-control" value="<?php echo htmlspecialchars($user['apellidos']); ?>" required>

      <label>Cedula</label>
      <input type="text" name="cedula" class="form-control" value="<?php echo htmlspecialchars($user['cedula']); ?>">

      <label>Telefono (WhatsApp)</label>
      <input type="text" name="numero" class="form-control" value="<?php echo htmlspecialchars($user['numero']); ?>">

      <label>Resena Personal</label>
      <textarea name="resena_personal" class="form-control" rows="3"><?php echo htmlspecialchars($user['resena_personal']); ?></textarea>

      <button type="submit" class="btn btn-primary w-100">Actualizar Perfil</button>
    </form>
  </div>
</div>

<div class="modal" id="servicio">
  <div class="modal-box">
    <div class="modal-header">
      Servicio
      <span class="close" onclick="closeModal('servicio')">&times;</span>
    </div>

    <form id="servicioForm" class="modal-body" enctype="multipart/form-data">
      <input type="hidden" name="action" value="servicio">

      <label>Imagen del servicio</label>
      <input type="file" name="imagen" accept="image/*" required>

      <label>Nombre del servicio</label>
      <input type="text" name="nombre_servicio" placeholder="Servicio o especialidad" required>

      <label>Resena</label>
      <textarea name="resena" placeholder="Descripcion del servicio"></textarea>

      <label>Enlace</label>
      <input type="url" name="enlace" placeholder="https://">

      <button class="submit">Guardar</button>
    </form>
  </div>
</div>

<div class="modal" id="cuenta">
  <div class="modal-box">
    <div class="modal-header">
      Cuenta bancaria
      <span class="close" onclick="closeModal('cuenta')">&times;</span>
    </div>

    <form id="cuentaForm" class="modal-body">
      <input type="hidden" name="action" value="cuenta">

      <label>Banco</label>
      <select name="banco" required>
        <option>BHD</option>
        <option>Ademi</option>
        <option>Ban Reservas</option>
        <option>Santa Cruz</option>
      </select>

      <label>Tipo de cuenta</label>
      <select name="tipo">
        <option>Ahorro</option>
        <option>Corriente</option>
      </select>

      <label>Numero de cuenta</label>
      <input type="text" name="numero" placeholder="Numero de cuenta" required>

      <button class="submit">Guardar</button>
    </form>
  </div>
</div>

<div class="modal" id="cartera">
  <div class="modal-box">
    <div class="modal-header">
      Cartera cripto
      <span class="close" onclick="closeModal('cartera')">&times;</span>
    </div>

    <form id="cryptoForm" class="modal-body">
      <input type="hidden" name="action" value="crypto">

      <label>Criptomoneda</label>
      <select name="moneda">
        <option>BTC</option>
        <option>ETHER</option>
      </select>

      <label>Red</label>
      <select name="red">
        <option value="BTC">BTC</option>
        <option value="ERC20">ERC20</option>
        <option value="TRC20">TRC20</option>
      </select>

      <label>Direccion</label>
      <input type="text" name="direccion" placeholder="Direccion de la cartera" required>

      <button class="submit">Guardar</button>
    </form>
  </div>
</div>

<div class="modal" id="online">
  <div class="modal-box">
    <div class="modal-header">
      Pago online
      <span class="close" onclick="closeModal('online')">&times;</span>
    </div>

    <div class="modal-body">
      <div id="listaPagosOnline" style="margin-bottom:20px; border-bottom:1px solid #eee; padding-bottom:15px;">
        <p style="font-weight:bold; font-size:12px; color:#666; text-align:left;">Metodos actuales:</p>
        <?php if (count($pagos) > 0) : ?>
          <?php foreach ($pagos as $pago) : ?>
            <div style="display:flex; justify-content:space-between; align-items:center; background:#f9f9f9; padding:8px 12px; border-radius:8px; margin-bottom:5px;">
              <span style="font-size:14px; font-weight:500;"><?php echo htmlspecialchars($pago['plataforma']); ?></span>
              <i
                class="fa-solid fa-xmark"
                onclick="prepararEliminar(<?php echo (int) $pago['id']; ?>, 'pago')"
                style="color:#d9534f; cursor:pointer; padding:5px;"
              ></i>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p style="font-size:12px; color:#999;">No hay metodos guardados.</p>
        <?php endif; ?>
      </div>

      <form id="plataformaForm">
        <input type="hidden" name="action" value="pago">
        <label>Plataforma</label>
        <select name="plataforma">
          <option value="Zelle">Zelle</option>
          <option value="PayPal">PayPal</option>
        </select>

        <label>Enlace</label>
        <input type="url" name="enlace" placeholder="https://">
        <button class="submit" style="width:100%;">Guardar Nuevo</button>
      </form>
    </div>
  </div>
</div>

<div class="search-float-btn" onclick="openModal('searchModal')">
  <i class="fa-solid fa-magnifying-glass"></i>
</div>

<div class="modal" id="searchModal">
  <div class="modal-box">
    <div class="modal-header">
      Buscar por telefono
      <span class="close" onclick="closeModal('searchModal')">&times;</span>
    </div>

    <form id="searchForm" class="modal-body">
      <label>Numero telefonico</label>
      <input type="tel" id="telefonoBusqueda" placeholder="Ej: 8091234567" required>
      <button class="submit">Buscar</button>
      <div id="searchAlert" style="margin-top:15px; display:none;"></div>
    </form>
  </div>
</div>

<div id="settingsModal" class="settings-overlay">
  <div class="settings-box">
    <div class="settings-header">
      <span>Configuracion</span>
      <button type="button" class="cerrar-x" onclick="closeSettingsModal()">&times;</button>
    </div>
    <div class="settings-body">
      <p>La configuracion visual sigue disponible. La persistencia en base de datos no estaba implementada en la app original.</p>
      <a href="logout.php" class="settings-link">
        <i class="fas fa-sign-out-alt"></i> Cerrar Sesion
      </a>
    </div>
  </div>
</div>

<div class="modal" id="confirmModal">
  <div class="modal-box">
    <div class="modal-header">
      Confirmar accion
      <span class="close" onclick="closeConfirmModal()">&times;</span>
    </div>
    <div class="modal-body">
      <p id="confirmText"></p>
      <button onclick="confirmAction()">Confirmar</button>
      <button onclick="closeConfirmModal()">Cancelar</button>
    </div>
  </div>
</div>

<script>
let elementoAEliminar = { id: null, tipo: null };

function openModal(id) { document.getElementById(id).style.display = "flex"; }
function closeModal(id) { document.getElementById(id).style.display = "none"; }

function openProfileModal() { openModal("profileModal"); }
function closeProfileModal() { closeModal("profileModal"); }

function openSettingsModal() {
  document.getElementById("settingsModal").style.display = "flex";
}

function closeSettingsModal() {
  document.getElementById("settingsModal").style.display = "none";
}

window.onclick = function(event) {
  const settingsModal = document.getElementById("settingsModal");
  if (event.target === settingsModal) {
    closeSettingsModal();
  }
};

function openMiniProfile(trigger, id, img, title, text) {
  const url = trigger.dataset.link || "";
  document.getElementById("modalImg").src = img;
  document.getElementById("modalTitle").innerText = title;
  document.getElementById("modalText").innerText = text;
  document.getElementById("modalLink").href = url;
  document.getElementById("modalLinkContainer").style.display = url ? "block" : "none";

  const btnEliminar = document.getElementById("btnEliminarServicio");
  btnEliminar.onclick = function() {
    document.querySelectorAll(".menu-dropdown").forEach((menu) => menu.style.display = "none");
    closeModal("modal");
    prepararEliminar(id, "servicio");
  };

  openModal("modal");
}

function openTab(evt, id) {
  document.querySelectorAll(".tabcontent").forEach((tab) => tab.style.display = "none");
  document.querySelectorAll(".tablink").forEach((button) => button.classList.remove("active"));
  document.getElementById(id).style.display = "block";
  evt.currentTarget.classList.add("active");
}

function toggleGate(btn) {
  const gate = btn.closest(".card").querySelector(".gate");
  const icon = btn.querySelector("i");
  gate.style.maxHeight = gate.style.maxHeight ? null : gate.scrollHeight + "px";
  icon.className = gate.style.maxHeight ? "fa-regular fa-eye-slash" : "fa-regular fa-eye";
}

function copyAuto(btn) {
  const text = btn.parentElement.querySelector(".copy-text").innerText;
  navigator.clipboard.writeText(text);
  btn.querySelector("i").className = "fa-solid fa-check";
  setTimeout(() => btn.querySelector("i").className = "fa-regular fa-copy", 1500);
}

function toggleCardMenu(icon) {
  document.querySelectorAll(".menu-dropdown").forEach((menu) => menu.style.display = "none");
  icon.nextElementSibling.style.display = "flex";
}

function prepararEliminar(id, tipo) {
  elementoAEliminar = { id, tipo };
  document.getElementById("confirmText").innerText = "Estas seguro de que deseas eliminar este elemento?";
  openModal("confirmModal");
}

function closeConfirmModal() {
  closeModal("confirmModal");
}

function confirmAction() {
  if (!elementoAEliminar.id) {
    closeConfirmModal();
    return;
  }

  $.ajax({
    url: "procesar.php",
    type: "POST",
    data: {
      action: "eliminar",
      id: elementoAEliminar.id,
      tipo: elementoAEliminar.tipo
    },
    success: function(res) {
      const respuesta = typeof res === "string" ? JSON.parse(res) : res;
      if (respuesta.status === "ok") {
        location.reload();
      } else {
        alert("Error: " + (respuesta.msg || "No se pudo eliminar"));
      }
    }
  });

  closeConfirmModal();
}

function sharePage() {
  if (navigator.share) {
    navigator.share({
      title: <?php echo json_encode("Perfil de " . $user['nombres']); ?>,
      url: location.href
    });
  } else {
    alert("Comparte este enlace: " + location.href);
  }
}

function enviarFormulario(selector, recargar = true) {
  $(selector).submit(function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: "procesar.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function(res) {
        const respuesta = typeof res === "string" ? JSON.parse(res) : res;
        if (respuesta.status === "ok") {
          if (recargar) {
            location.reload();
          }
        } else {
          alert(respuesta.msg || "No se pudo guardar");
        }
      }
    });
  });
}

enviarFormulario("#perfilForm");
enviarFormulario("#servicioForm");
enviarFormulario("#cuentaForm");
enviarFormulario("#cryptoForm");
enviarFormulario("#plataformaForm");

$("#searchForm").submit(function(e) {
  e.preventDefault();

  $.ajax({
    url: "buscar_telefono.php",
    type: "POST",
    data: { telefono: $("#telefonoBusqueda").val() },
    success: function(res) {
      const alertBox = $("#searchAlert");

      if (String(res).trim() === "no_encontrado") {
        alertBox
          .removeClass()
          .addClass("alert-error")
          .text("Numero no encontrado")
          .show();
      } else {
        window.location.href = "perfildecuentas.php?numero=" + encodeURIComponent($("#telefonoBusqueda").val());
      }
    }
  });
});
</script>

</body>
</html>
<?php mysqli_close($conn); ?>
