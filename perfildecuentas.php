<?php
session_start();
require_once 'db.php';

// Validar que el usuario esté logueado si es un requisito general
if (!isset($_SESSION['user_id'])) {
  ///  header("Location: login.html");
  ///  exit();
}

// 1. Recibir el número mediante método GET (URL)
if (isset($_GET['numero']) && !empty($_GET['numero'])) {
    // Escapar el dato para prevenir Inyección SQL
    $numero = mysqli_real_escape_string($conn, $_GET['numero']);
} else {
    // Si no se envía un número en la URL, detenemos la ejecución
    die("Número de teléfono no proporcionado en la URL. (Ejemplo: ?numero=8497071192)");
}

/* ===== USUARIO ===== */
// Añadidas comillas simples alrededor de $numero por si contiene caracteres especiales
$sql = "SELECT * FROM usuarios WHERE numero = '$numero'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    // 2. CORRECCIÓN IMPORTANTE: Definir el $user_id a partir del usuario encontrado
    // Asumo que la llave primaria en tu tabla 'usuarios' se llama 'id'
    $user_id = $user['id']; 
} else {
    die("Usuario no encontrado");
}


/* ===== SERVICIOS ===== */
$sql2 = "SELECT * FROM servicios WHERE usuario_id = '$user_id'";
$result2 = mysqli_query($conn, $sql2);


/* ===== CUENTAS BANCARIAS ===== */
$sqlCuentas = "SELECT * FROM cuentas_bancarias WHERE usuario_id = '$user_id'";
$resultCuentas = mysqli_query($conn, $sqlCuentas);


/* ===== CRIPTOS ===== */
$sqlCripto = "SELECT * FROM cripto_wallets WHERE usuario_id = '$user_id'";
$resultCripto = mysqli_query($conn, $sqlCripto);

/* ===== PAGOS ===== */
$sqlPagos = "SELECT * FROM pagos_online WHERE usuario_id = '$user_id'";
$resultPagos = mysqli_query($conn, $sqlPagos);
?>

<!DOCTYPE html>
<html lang="es">
<head>

  <!-- ================= CONFIGURACIÓN BÁSICA ================= -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de <?php echo htmlspecialchars($user['nombres']); ?></title>

  <!-- ================= ICONOS ================= -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- ================= ESTILOS ================= -->
  <link rel="stylesheet" href="styles/style.css">

</head>
<body>

<!-- =========================================================
=                         HEADER                            =
========================================================= -->
<header class="header">

  <!-- BOTÓN COMPARTIR -->
  <div class="share-page" onclick="sharePage()" title="Compartir">
    <i class="fa-solid fa-share-nodes"></i>
  </div>

  <!-- PERFIL PRINCIPAL -->
  <div class="profile-header">
    <div class="profile-wrapper">
      <!-- Si tienes una columna foto_perfil en la bd, úsala aquí. De lo contrario usa la default -->
      <img src="<?php echo isset($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : 'imagen/alcala.png'; ?>"
           class="profile-main"
           onclick="openProfileModal()"
           alt="<?php echo htmlspecialchars($user['nombres']); ?>" />

    </div>
  </div>

<p><strong><?php echo htmlspecialchars($user['nombres']); ?></strong></p>
<p style="color:#666;">Cédula: <?php echo htmlspecialchars($user['cedula']); ?></p>

  
  <!-- MINI PERFILES -->
<div class="mini-profiles-scroll">

<?php
if (mysqli_num_rows($result2) > 0) {
  while($servicio = mysqli_fetch_assoc($result2)) {
?>

    <div class="mini-profile"
  onclick="openMiniProfile(
    '<?php echo htmlspecialchars($servicio['imagen']); ?>',
    '<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>',
    '<?php echo htmlspecialchars($servicio['resena']); ?>',
    '<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>'
  )">

      <img src="<?php echo htmlspecialchars($servicio['imagen']); ?>">
      <span><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></span>

    </div>

<?php
  }
}
?>



</div>
  
</header>

<!-- =========================================================
=                       NAVEGACIÓN                          =
========================================================= -->
<nav class="tab-buttons">
  <button class="tablink active" onclick="openTab(event,'Cuentas')">Cuentas</button>
  <button class="tablink" onclick="openTab(event,'Criptos')">Criptos</button>
  <button class="tablink" onclick="openTab(event,'Pagos')">Online</button>
</nav>

<!-- =========================================================
=                       CONTENIDO                           =
========================================================= -->

<!-- ===== CUENTAS ===== -->
<main id="Cuentas" class="tabcontent" style="display:block;">
<div class="cards">

<?php
if (mysqli_num_rows($resultCuentas) > 0) {
  while($cuenta = mysqli_fetch_assoc($resultCuentas)) {
?>

    <div class="card">
      <div class="card-top">

        <img src="<?php echo htmlspecialchars($cuenta['imagen']); ?>">

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

<?php
  }
} else {
  echo "<p>Este usuario no tiene cuenta registrada</p>";
}
?>

</div>
</main>


<!-- ===== CRIPTOS ===== -->
<main id="Criptos" class="tabcontent">
 <div class="cards">

<?php
if (mysqli_num_rows($resultCripto) > 0) {
  while($crypto = mysqli_fetch_assoc($resultCripto)) {
?>

    <div class="card">
      <div class="card-top">

        <img src="<?php echo htmlspecialchars($crypto['imagen']); ?>">

        <div class="card-content">
          <h3>
            <?php echo htmlspecialchars($crypto['moneda']); ?> (Red <?php echo htmlspecialchars($crypto['red']); ?>)
          </h3>
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

<?php
  }
} else {
  echo "<p>Este usuario no tiene cuenta registrada</p>";
}
?>

</div>
</main>

<!-- ===== PAGOS ===== -->
<main id="Pagos" class="tabcontent">
<div class="payment-container">

<?php
if (mysqli_num_rows($resultPagos) > 0) {
  while($pago = mysqli_fetch_assoc($resultPagos)) {
    // Escapar los atributos del HTML para prevenir inyecciones
    $clase = strtolower(str_replace(' ', '', htmlspecialchars($pago['plataforma'])));
    $enlace = htmlspecialchars($pago['enlace']);
    $plataforma = htmlspecialchars($pago['plataforma']);
?>

<button class="pay-btn <?php echo $clase; ?>"
  onclick="window.open('<?php echo $enlace; ?>', '_blank')">
  <?php echo $plataforma; ?>
</button>

<?php
  }
} else {
  echo "<p>Este usuario no tiene cuenta registrada</p>";
}
?>

</div>
</main>

<!-- =========================================================
=                       MODALES                             =
========================================================= -->

<!-- PERFIL (Actualizado para ser dinámico) -->
<div id="profileModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeProfileModal()">&times;</span>
    <img src="<?php echo isset($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : 'imagen/alcala.png'; ?>" width="100" style="border-radius:50%;">
    <h3><?php echo htmlspecialchars($user['nombres']); ?></h3>
    <p><?php echo isset($user['resena_personal']) ? htmlspecialchars($user['resena_personal']) : 'Emprendedor & Desarrollador'; ?></p>
  </div>
</div>

<!-- MINI PERFIL -->
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

<!-- =========================================================
=                     JAVASCRIPT                            =
========================================================= -->
<script>

/* ================= MODALES ================= */
function openModal(id){ document.getElementById(id).style.display="flex"; }
function closeModal(id){ document.getElementById(id).style.display="none"; }

function openProfileModal(){ openModal("profileModal"); }
function closeProfileModal(){ closeModal("profileModal"); }

/* ================= MINI PERFIL ================= */
function openMiniProfile(img,title,text,url){
  modalImg.src = img;
  modalTitle.innerText = title;
  modalText.innerText = text;
  modalLink.href = url;
  modalLinkContainer.style.display = url ? "block" : "none";
  openModal("modal");
}
function closeMiniProfile(){ closeModal("modal"); }

/* ================= TABS ================= */
function openTab(evt,id){
  document.querySelectorAll(".tabcontent").forEach(t=>t.style.display="none");
  document.querySelectorAll(".tablink").forEach(b=>b.classList.remove("active"));
  document.getElementById(id).style.display="block";
  evt.currentTarget.classList.add("active");
}

/* ================= UTILIDADES ================= */
function toggleGate(btn){
  const gate = btn.closest(".card").querySelector(".gate");
  const icon = btn.querySelector("i");
  gate.style.maxHeight = gate.style.maxHeight ? null : gate.scrollHeight+"px";
  icon.className = gate.style.maxHeight ? "fa-regular fa-eye-slash" : "fa-regular fa-eye";
}

function copyAuto(btn){
  const text = btn.parentElement.querySelector(".copy-text").innerText;
  navigator.clipboard.writeText(text);
  btn.querySelector("i").className="fa-solid fa-check";
  setTimeout(()=>btn.querySelector("i").className="fa-regular fa-copy",1500);
}

/* ================= MENÚ TARJETA ================= */
function toggleCardMenu(icon){
  document.querySelectorAll(".menu-dropdown").forEach(m=>m.style.display="none");
  icon.nextElementSibling.style.display="flex";
}

/* ================= CONFIRMACIÓN ================= */
let currentAction=null;
function openConfirmModal(action){
  currentAction=action;
  confirmText.innerText = action==="edit"
    ? "¿Deseas editar este elemento?"
    : "¿Deseas eliminar este elemento?";
  openModal("confirmModal");
}
function closeConfirmModal(){ closeModal("confirmModal"); }
function confirmAction(){ closeConfirmModal(); }

/* ================= OTROS ================= */
function sharePage(){
  navigator.share
    ? navigator.share({title:"Perfil de <?php echo addslashes($user['nombres']); ?>", url:location.href})
    : alert("Enlace copiado");
}
function handlePayment(m){ alert("Redirigiendo a "+m); }

</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

$(document).ready(function(){

/* PERFIL */
$("#perfilForm").submit(function(e){
  e.preventDefault();
  let formData = new FormData(this);
  $.ajax({
    url: "procesar.php",
    type:"POST",
    data:formData,
    contentType:false,
    processData:false,
    success:function(res){
      alert("Perfil guardado");
    }
  });
});


/* SERVICIO */
$("#servicioForm").submit(function(e){
  e.preventDefault();
  let formData = new FormData(this);
  $.ajax({
    url: "procesar.php",
    type:"POST",
    data:formData,
    contentType:false,
    processData:false,
    success:function(res){
      alert("Servicio guardado");
    }
  });
});


/* CUENTA BANCARIA */
$("#cuentaForm").submit(function(e){
  e.preventDefault();
  $.ajax({
    url: "procesar.php",
    type:"POST",
    data:$(this).serialize(),
    success:function(res){
      alert("Cuenta guardada");
    }
  });
});


/* CRYPTO */
$("#cryptoForm").submit(function(e){
  e.preventDefault();
  $.ajax({
    url: "procesar.php",
    type:"POST",
    data:$(this).serialize(),
    success:function(res){
      alert("Wallet guardada");
    }
  });
});


/* PLATAFORMA */
$("#plataformaForm").submit(function(e){
  e.preventDefault();
  $.ajax({
    url: "procesar.php",
    type:"POST",
    data:$(this).serialize(),
    success:function(res){
      alert("Plataforma guardada");
    }
  });
});

});

</script>
  
</body>
</html>
<?php mysqli_close($conn); ?>