<?php
session_start();

require_once 'db.php';
require_once 'helpers.php';

$numero = app_normalize_phone($_GET['numero'] ?? '');

if ($numero === '') {
    die("Numero de telefono no proporcionado en la URL. (Ejemplo: ?numero=8497071192)");
}

$stmtUser = mysqli_prepare($conn, "SELECT * FROM usuarios WHERE numero = ? LIMIT 1");
mysqli_stmt_bind_param($stmtUser, "s", $numero);
mysqli_stmt_execute($stmtUser);
$resultUser = mysqli_stmt_get_result($stmtUser);

if (!$resultUser || mysqli_num_rows($resultUser) === 0) {
    die("Usuario no encontrado");
}

$user = mysqli_fetch_assoc($resultUser);
$user_id = (int) $user['id'];

$result2 = mysqli_query($conn, "SELECT * FROM servicios WHERE usuario_id = {$user_id}");
$resultCuentas = mysqli_query($conn, "SELECT * FROM cuentas_bancarias WHERE usuario_id = {$user_id}");
$resultCripto = mysqli_query($conn, "SELECT * FROM cripto_wallets WHERE usuario_id = {$user_id}");
$resultPagos = mysqli_query($conn, "SELECT * FROM pagos_online WHERE usuario_id = {$user_id}");

$profileImage = app_asset_url($user['imagen'] ?? 'perfil.png', ['uploads', 'imagen'], 'uploads/perfil.png');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de <?php echo htmlspecialchars($user['nombres']); ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<header class="header">
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
    </div>
  </div>

  <p><strong><?php echo htmlspecialchars($user['nombres']); ?></strong></p>
  <p style="color:#666;">Cedula: <?php echo htmlspecialchars($user['cedula']); ?></p>

  <div class="mini-profiles-scroll">
    <?php if ($result2 && mysqli_num_rows($result2) > 0) : ?>
      <?php while ($servicio = mysqli_fetch_assoc($result2)) : ?>
        <div
          class="mini-profile"
          onclick='openMiniProfile(
            <?php echo json_encode(app_asset_url($servicio["imagen"] ?? "", ["uploads", "imagen"], "imagen/perfil.png")); ?>,
            <?php echo json_encode($servicio["nombre_servicio"] ?? ""); ?>,
            <?php echo json_encode($servicio["resena"] ?? ""); ?>,
            <?php echo json_encode($servicio["enlace"] ?? ""); ?>
          )'
        >
          <img src="<?php echo htmlspecialchars(app_asset_url($servicio['imagen'] ?? '', ['uploads', 'imagen'], 'imagen/perfil.png')); ?>">
          <span><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></span>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</header>

<nav class="tab-buttons">
  <button class="tablink active" onclick="openTab(event,'Cuentas')">Cuentas</button>
  <button class="tablink" onclick="openTab(event,'Criptos')">Criptos</button>
  <button class="tablink" onclick="openTab(event,'Pagos')">Online</button>
</nav>

<main id="Cuentas" class="tabcontent" style="display:block;">
  <div class="cards">
    <?php if ($resultCuentas && mysqli_num_rows($resultCuentas) > 0) : ?>
      <?php while ($cuenta = mysqli_fetch_assoc($resultCuentas)) : ?>
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
          </div>
        </div>
      <?php endwhile; ?>
    <?php else : ?>
      <p>Este usuario no tiene cuenta registrada</p>
    <?php endif; ?>
  </div>
</main>

<main id="Criptos" class="tabcontent">
  <div class="cards">
    <?php if ($resultCripto && mysqli_num_rows($resultCripto) > 0) : ?>
      <?php while ($crypto = mysqli_fetch_assoc($resultCripto)) : ?>
        <div class="card">
          <div class="card-top">
            <img src="<?php echo htmlspecialchars(app_asset_url($crypto['imagen'] ?? '', ['imagen', 'uploads'], 'imagen/images.png')); ?>">
            <div class="card-content">
              <h3><?php echo htmlspecialchars($crypto['moneda']); ?> (Red <?php echo htmlspecialchars($crypto['red']); ?>)</h3>
            </div>
            <div class="toggle-btn" onclick="toggleGate(this)">
              <i class="fa-regular fa-eye"></i>
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
      <?php endwhile; ?>
    <?php else : ?>
      <p>Este usuario no tiene cuenta registrada</p>
    <?php endif; ?>
  </div>
</main>

<main id="Pagos" class="tabcontent">
  <div class="payment-container">
    <?php if ($resultPagos && mysqli_num_rows($resultPagos) > 0) : ?>
      <?php while ($pago = mysqli_fetch_assoc($resultPagos)) : ?>
        <?php $clase = strtolower(str_replace(' ', '', $pago['plataforma'])); ?>
        <button
          class="pay-btn <?php echo htmlspecialchars($clase); ?>"
          onclick='window.open(<?php echo json_encode($pago["enlace"]); ?>, "_blank")'
        >
          <?php echo htmlspecialchars($pago['plataforma']); ?>
        </button>
      <?php endwhile; ?>
    <?php else : ?>
      <p>Este usuario no tiene cuenta registrada</p>
    <?php endif; ?>
  </div>
</main>

<div id="profileModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeProfileModal()">&times;</span>
    <img src="<?php echo htmlspecialchars($profileImage); ?>" width="100" style="border-radius:50%;">
    <h3><?php echo htmlspecialchars($user['nombres']); ?></h3>
    <p><?php echo htmlspecialchars($user['resena_personal'] ?? 'Sin descripcion personal.'); ?></p>
  </div>
</div>

<div id="modal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeMiniProfile()">&times;</span>
    <img id="modalImg" width="80" style="border-radius:50%;">
    <h3 id="modalTitle"></h3>
    <p id="modalText"></p>
    <div id="modalLinkContainer">
      <a id="modalLink" target="_blank">Visitar</a>
    </div>
  </div>
</div>

<script>
function openModal(id) { document.getElementById(id).style.display = "flex"; }
function closeModal(id) { document.getElementById(id).style.display = "none"; }

function openProfileModal() { openModal("profileModal"); }
function closeProfileModal() { closeModal("profileModal"); }

function openMiniProfile(img, title, text, url) {
  modalImg.src = img;
  modalTitle.innerText = title;
  modalText.innerText = text;
  modalLink.href = url;
  modalLinkContainer.style.display = url ? "block" : "none";
  openModal("modal");
}

function closeMiniProfile() { closeModal("modal"); }

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
</script>

</body>
</html>
<?php mysqli_close($conn); ?>
