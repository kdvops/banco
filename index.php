<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===== USUARIO ===== */
$sql = "SELECT * FROM usuarios WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    die("Usuario no encontrado");
}


/* ===== SERVICIOS ===== */
$sql2 = "SELECT * FROM servicios WHERE usuario_id = $user_id";
$result2 = mysqli_query($conn, $sql2);


/* ===== CUENTAS BANCARIAS ===== */
$sqlCuentas = "SELECT * FROM cuentas_bancarias WHERE usuario_id = $user_id";
$resultCuentas = mysqli_query($conn, $sqlCuentas);


/* ===== CRIPTOS ===== */
$sqlCripto = "SELECT * FROM cripto_wallets WHERE usuario_id = $user_id";
$resultCripto = mysqli_query($conn, $sqlCripto);

/* ===== PAGOS ===== */
$sqlPagos = "SELECT * FROM pagos_online WHERE usuario_id = $user_id";
$resultPagos = mysqli_query($conn, $sqlPagos);
?>



<!DOCTYPE html>
<html lang="es">
<head>

  <!-- ================= CONFIGURACIÓN BÁSICA ================= -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tarjetas con Perfiles - Juan M Alcalá</title>

  <!-- ================= ICONOS ================= -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- ================= ESTILOS ================= -->
  <link rel="stylesheet" href="styles/style.css">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>

<style>
/* Botón Flotante Superior Izquierda */
.btn-ajustes-fijo {
    position: fixed;
    top: 20px;
    left: 20px;
    width: 45px;
    height: 45px;
    background: #444;
    color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* Capa de fondo del modal */
.capa-oscura {
    display: none; 
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 10000;
    justify-content: center;
    align-items: center;
}

/* Caja del modal */
.ventana-modal {
    background: white;
    width: 90%;
    max-width: 320px;
    border-radius: 12px;
    overflow: hidden;
    font-family: sans-serif;
}

.encabezado-modal {
    padding: 15px;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    border-bottom: 1px solid #ddd;
}

.cerrar-x { border: none; background: none; font-size: 20px; cursor: pointer; }

.cuerpo-modal { padding: 15px; }

/* Estilo de los Switches */
.fila-ajuste {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.interruptor {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 22px;
}

.interruptor input { opacity: 0; width: 0; height: 0; }

.deslizador {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
    transition: .3s;
    border-radius: 34px;
}

.deslizador:before {
    position: absolute;
    content: "";
    height: 16px; width: 16px;
    left: 3px; bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
}

input:checked + .deslizador { background-color: #4CAF50; }
input:checked + .deslizador:before { transform: translateX(18px); }

.divisor { border: 0; border-top: 1px solid #eee; margin: 15px 0; }

.boton-salir {
    display: block;
    text-align: center;
    color: #d9534f;
    text-decoration: none;
    font-weight: bold;
    padding: 10px;
}








/* FUERZA LA SEPARACIÓN DE LOS BOTONES EN EL MINI PERFIL */
.modal-perfil-ajustado {
    position: relative !important;
    padding-top: 70px !important; /* Espacio extra para que los botones no tapen la foto */
    text-align: center;
    overflow: visible !important;
}

/* Menú de 3 puntos (Izquierda) */
.menu-extremo-izquierdo {
    position: absolute !important;
    top: 15px !important;
    left: 15px !important;
    right: auto !important;
    margin: 0 !important;
    z-index: 10;
}

/* Botón de cerrar (Derecha) */
.btn-cerrar-extremo {
    position: absolute !important;
    top: 10px !important;
    right: 15px !important;
    left: auto !important;
    margin: 0 !important;
    font-size: 28px !important;
    z-index: 10;
}

/* Imagen circular fija */
.img-modal-perfil {
    width: 90px !important;
    height: 90px !important;
    border-radius: 50% !important;
    object-fit: cover !important; /* Mantiene la proporción */
    display: block;
    margin: 0 auto 15px;
    border: 3px solid #eee;
}







/* FORZAR COMPORTAMIENTO DEL MODAL */
.modal-perfil-ajustado {
    position: relative !important;
    padding-top: 60px !important; /* Baja el contenido para que los botones tengan espacio */
    text-align: center;
    overflow: visible !important;
}

/* Menú de 3 puntos (Extremo Izquierdo) */
.menu-extremo-izquierdo {
    position: absolute !important;
    top: 15px !important;
    left: 15px !important;
    right: auto !important;
    margin: 0 !important;
    z-index: 999; /* Asegura que esté arriba para poder tocarlo */
}

/* Botón cerrar (Extremo Derecho) */
.btn-cerrar-extremo {
    position: absolute !important;
    top: 10px !important;
    right: 15px !important;
    left: auto !important;
    margin: 0 !important;
    font-size: 30px !important; /* Más grande para que sea fácil de tocar */
    cursor: pointer !important;
    z-index: 999; 
    color: #333;
}

/* Imagen circular fija */
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
<body>

<!-- =========================================================
=                         HEADER                            =
========================================================= -->
<header class="header">


<div class="btn-ajustes-fijo" onclick="abrirModal()">
    <i class="fas fa-cog"></i>
</div>

  <!-- BOTÓN COMPARTIR -->
  <div class="share-page" onclick="sharePage()" title="Compartir">
    <i class="fa-solid fa-share-nodes"></i>
  </div>

  <!-- PERFIL PRINCIPAL -->
  <div class="profile-header">
    <div class="profile-wrapper">
<img src="<?php echo !empty($user['imagen']) ? 'uploads/' . htmlspecialchars($user['imagen']) : 'uploads/perfil.png'; ?>"
     class="profile-main"
     onclick="openProfileModal()"
     alt="<?php echo htmlspecialchars($user['nombres']); ?>" />


      <button class="edit-btn" onclick="openModal('perfil')">
        <i class="fa-solid fa-pen"></i>
      </button>
    </div>
  </div>

<p><strong><?php echo $user['nombres']; ?></strong></p>
<p style="color:#666;">Cédula: <?php echo $user['cedula']; ?></p>

  
  <!-- MINI PERFILES -->
<div class="mini-profiles-scroll">

<?php
if (mysqli_num_rows($result2) > 0) {
  while($servicio = mysqli_fetch_assoc($result2)) {
?>

 <div class="mini-profile"
onclick="openMiniProfile(
    '<?php echo $servicio['id']; ?>', 
    '<?php echo htmlspecialchars($servicio['imagen']); ?>',
    '<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>',
    '<?php echo htmlspecialchars($servicio['resena']); ?>',
    '<?php echo htmlspecialchars($servicio['nombre_servicio']); ?>' // Aquí repites el nombre en lugar del link
)">
  <img src="<?php echo $servicio['imagen']; ?>">
  <span><?php echo $servicio['nombre_servicio']; ?></span>
</div>

<?php
  }
}
?>

    <div class="mini-profile">
      <div class="add-circle" onclick="openModal('servicio')">+</div>
      <span>Agregar</span>
    </div>

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

       <img src="imagen/<?php echo htmlspecialchars($cuenta['imagen']); ?>">

        <div class="card-content">
          <h3><?php echo $cuenta['banco']; ?></h3>

          <div class="copy-row">
            <?php echo $cuenta['tipo_cuenta']; ?> -
            <span class="copy-text"><?php echo $cuenta['numero_cuenta']; ?></span>

            <button class="copy-btn" onclick="copyAuto(this)">
              <i class="fa-regular fa-copy"></i>
            </button>
          </div>
        </div>

        <!-- MENÚ -->
        <div class="card-menu">
          <i class="fa-solid fa-ellipsis-vertical" onclick="toggleCardMenu(this)"></i>
          <div class="menu-dropdown">
  
<button class="danger" onclick="prepararEliminar(<?php echo $cuenta['id']; ?>, 'cuenta')">
  <i class="fa-solid fa-trash"></i> Eliminar
</button>
          </div>
        </div>

      </div>
    </div>

<?php
  }
} else {
  echo "<p>No tienes cuentas registradas</p>";
}
?>

    <!-- BOTÓN AGREGAR -->
    <div class="card-add" onclick="openModal('cuenta')">
      <i class="fa-solid fa-building-columns"></i> Agregar Cuenta
    </div>

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

        
		 <img src="imagen/<?php echo htmlspecialchars($crypto['imagen']); ?>">

        <div class="card-content">
          <h3>
            <?php echo $crypto['moneda']; ?> (Red <?php echo $crypto['red']; ?>)
          </h3>
        </div>

        <div class="toggle-btn" onclick="toggleGate(this)">
          <i class="fa-regular fa-eye"></i>
        </div>

        <div class="card-menu">
          <i class="fa-solid fa-ellipsis-vertical" onclick="toggleCardMenu(this)"></i>
          <div class="menu-dropdown">
           <button class="danger" onclick="prepararEliminar(<?php echo $crypto['id']; ?>, 'crypto')">Eliminar</button>     </div>
        </div>

      </div>

      <div class="gate">
        <div class="gate-inner">
          <div class="copy-row">

            <span class="copy-text"><?php echo $crypto['direccion']; ?></span>

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
  echo "<p>No tienes wallets registradas</p>";
}
?>

<div class="card-add" onclick="openModal('cartera')">
  <i class="fa-brands fa-bitcoin"></i> Agregar Cartera
</div>

</div>
</main>

<!-- ===== PAGOS ===== -->
<main id="Pagos" class="tabcontent">
<div class="payment-container">

<?php
//sqlPagos = "SELECT * FROM pagos_online WHERE usuario_id = $user_id";
//$resultPagos = mysqli_query($conn, $sqlPagos);

if (mysqli_num_rows($resultPagos) > 0) {
  while($pago = mysqli_fetch_assoc($resultPagos)) {
?>

<?php
$clase = strtolower(str_replace(' ', '', $pago['plataforma']));
?>

<button class="pay-btn <?php echo $clase; ?>"
  onclick="window.open('<?php echo $pago['enlace']; ?>', '_blank')">

  <?php echo $pago['plataforma']; ?>

</button>


<?php
  }
} else {
  echo "<p>No tienes métodos de pago registrados</p>";
}
?>

    <!-- BOTÓN AGREGAR -->
    <div class="card-add" onclick="openModal('online')">
      <i class="fa-brands fa-wallet"></i> Agregar Cartera online
    </div>
	

</div>
</main>

<!-- =========================================================
=                       MODALES                             =
========================================================= -->

<!-- PERFIL -->
<!-- PERFIL (Modal dinámico) -->
<div id="profileModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeProfileModal()">&times;</span>
    
<?php 
      // Usar siempre la columna 'imagen'
      $fotoPerfil = !empty($user['imagen']) ? $user['imagen'] : 'perfil.png';
      $rutaFinal = "uploads/" . $fotoPerfil;
    ?>
    
    <img src="<?php echo htmlspecialchars($rutaFinal); ?>" 
         width="100" 
         style="border-radius:50%; object-fit: cover; aspect-ratio: 1/1;"
         onerror="this.src='uploads/perfil.png';">

    <h3><?php echo htmlspecialchars($user['nombres'] . " " . $user['apellidos']); ?></h3>
    
    <!-- Si tienes un campo de descripción en la tabla usuarios puedes ponerlo aquí, 
         si no, dejamos uno por defecto o la cédula -->
    <p style="color: #666;"><?php echo htmlspecialchars($user['resena_personal']); ?></p>
  </div>
</div>




<!-- MINI PERFIL -->
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

  

  
  
  
  
  
  
  
  
  <!-- =========================================================
=           MODALES Formulario perfil formulario                       =
========================================================= -->

  
  <div class="modal" id="perfil">
  <div class="modal-box">

    <div class="modal-header">
      Perfil
      <span class="close" onclick="closeModal('perfil')">×</span>
    </div>

<!-- Dentro de index.php, busca el form id="perfilForm" y reemplázalo o verifícalo -->
<form id="perfilForm" enctype="multipart/form-data" class="modal-body" >
    <!-- Este campo es VITAL para que procesar.php sepa qué hacer -->
    <input type="hidden" name="action" value="perfil">
    
    <div class="mb-3">
        <label>Foto de Perfil</label>
        <input type="file" name="foto" class="form-control">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Nombres</label>
            <input type="text" name="nombres" class="form-control" value="<?php echo $user['nombres']; ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Apellidos</label>
            <input type="text" name="apellidos" class="form-control" value="<?php echo $user['apellidos']; ?>" required>
        </div>
    </div>
    <div class="mb-3">
        <label>Cédula</label>
        <input type="text" name="cedula" class="form-control" value="<?php echo $user['cedula']; ?>">
    </div>
    <div class="mb-3">
        <label>Teléfono (WhatsApp)</label>
        <!-- Cambiado name="telefono" a name="numero" para coincidir con SQL -->
        <input type="text" name="numero" class="form-control" value="<?php echo $user['numero']; ?>">
    </div>
    <div class="mb-3">
        <label>Reseña Personal</label>
        <textarea name="resena_personal" class="form-control" rows="3"><?php echo $user['resena_personal']; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary w-100">Actualizar Perfil</button>
</form>

  </div>
</div>
  
    <!-- =========================================================
=           MODALES Formulario mini perfil                       =
========================================================= -->

  
  <div class="modal" id="servicio">
  <div class="modal-box">

    <div class="modal-header">
      Servicio
      <span class="close" onclick="closeModal('servicio')">×</span>
    </div>

    <form id="servicioForm" class="modal-body">

<input type="hidden" name="action" value="servicio">

      <label>Imagen del servicio</label>
      <input type="file" name="imagen" accept="image/*" required>

      <label>Nombre del servicio</label>
      <input type="text" name="nombre_servicio" placeholder="Servicio o especialidad">

      <label>Reseña</label>
      <textarea name="resena" placeholder="Descripción del servicio"></textarea>

      <label>Enlace</label>
      <input type="url" name="link" placeholder="https://">

      <button class="submit">Guardar</button>

    </form>

  </div>
</div>
  
  
      <!-- =========================================================
=           MODALES Formulario cuenta bancaria                      =
========================================================= -->

  
  <div class="modal" id="cuenta">
  <div class="modal-box">

    <div class="modal-header">
      Cuenta bancaria
      <span class="close" onclick="closeModal('cuenta')">×</span>
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
      <select name="tipo" >
        <option>Ahorro</option>
        <option>Corriente</option>
      </select>

      <label>Número de cuenta</label>
      <input type="text" name="numero" placeholder="Número de cuenta">

      <button class="submit">Guardar</button>

    </form>

  </div>
</div>

  <!-- =========================================================
=  MODALES Formulario Cartera cripto                      =
========================================================= -->

  
  <div class="modal" id="cartera">
  <div class="modal-box">

    <div class="modal-header">
      Cartera cripto
      <span class="close" onclick="closeModal('cartera')">×</span>
    </div>

    <form id="cryptoForm" class="modal-body">

<input type="hidden" name="action" value="crypto">

      <label>Criptomoneda</label>
       <select name="moneda" >
        <option>BTC</option>
       <option>ETHER</option>
      </select>

      <label>Red</label>
      <select name="red">
  <option value="BTC">BTC</option>
  <option value="ERC20">ERC20</option>
  <option value="TRC20">TRC20</option>
</select>

      <label>Dirección</label>
      <input type="text" name="direccion" placeholder="Dirección de la cartera">

      <button class="submit">Guardar</button>

    </form>

  </div>
</div>
  
  
  
    <!-- =========================================================
=  MODALES Formulario Pago online                    =
========================================================= -->

<div class="modal" id="online">
  <div class="modal-box">
    <div class="modal-header">
      Pago online
      <span class="close" onclick="closeModal('online')">×</span>
    </div>

    <div class="modal-body">
      <div id="listaPagosOnline" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
        <p style="font-weight: bold; font-size: 12px; color: #666; text-align: left;">Métodos actuales:</p>
        <?php
        // Reiniciamos el puntero del resultado de pagos si ya se usó antes
        mysqli_data_seek($resultPagos, 0); 
        if (mysqli_num_rows($resultPagos) > 0) {
          while($pago = mysqli_fetch_assoc($resultPagos)) {
        ?>
          <div style="display: flex; justify-content: space-between; align-items: center; background: #f9f9f9; padding: 8px 12px; border-radius: 8px; margin-bottom: 5px;">
            <span style="font-size: 14px; font-weight: 500;"><?php echo $pago['plataforma']; ?></span>
            <i class="fa-solid fa-xmark" 
               onclick="prepararEliminar(<?php echo $pago['id']; ?>, 'pago')" 
               style="color: #d9534f; cursor: pointer; padding: 5px;"></i>
          </div>
        <?php
          }
        } else {
          echo "<p style='font-size: 12px; color: #999;'>No hay métodos guardados.</p>";
        }
        ?>
      </div>

      <form id="plataformaForm">
        <input type="hidden" name="action" value="pago">
        <label>Plataforma</label>
        <select name="plataforma">
          <option value="Zelle">Zelle</option>
          <option value="PayPal">PayPal</option>
        </select>
        <label>Enlace</label>
        <input type="url" name="link" placeholder="https://">
        <button class="submit" style="width: 100%;">Guardar Nuevo</button>
      </form>
    </div>
  </div>
</div>










 
  <!-- BOTÓN BUSCAR -->
<div class="search-float-btn" onclick="openModal('searchModal')">
  <i class="fa-solid fa-magnifying-glass"></i>
</div>


<!-- MODAL BUSCAR POR TELÉFONO -->
<div class="modal" id="searchModal">
  <div class="modal-box">

    <div class="modal-header">
      Buscar por teléfono
      <span class="close" onclick="closeModal('searchModal')">×</span>
    </div>

    <form id="searchForm" class="modal-body">

      <label>Número telefónico</label>
      <input type="tel" id="telefonoBusqueda" placeholder="Ej: 8091234567" required>

      <button class="submit">Buscar</button>

      <!-- ALERTA -->
      <div id="searchAlert" style="margin-top:15px; display:none;"></div>

    </form>

  </div>
</div>












<div id="miModalAjustes" class="capa-oscura">
    <div class="ventana-modal">
        <div class="encabezado-modal">
            <span>Configuración</span>
            <button class="cerrar-x" onclick="cerrarModal()">&times;</button>
        </div>
        
        <div class="cuerpo-modal">
            <div class="fila-ajuste">
                <label>Cuenta pública</label>
                <label class="interruptor">
                    <input type="checkbox" id="sw-publica">
                    <span class="deslizador"></span>
                </label>
            </div>

            <div class="fila-ajuste">
                <label>Permitir búsqueda</label>
                <label class="interruptor">
                    <input type="checkbox" id="sw-busqueda">
                    <span class="deslizador"></span>
                </label>
            </div>

            <div class="fila-ajuste">
                <label>Permitir compartir</label>
                <label class="interruptor">
                    <input type="checkbox" id="sw-compartir">
                    <span class="deslizador"></span>
                </label>
            </div>

            <hr class="divisor">

            <a href="logout.php" class="boton-salir">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</div>









  
<!-- CONFIRMACIÓN -->
<div class="modal" id="confirmModal">
  <div class="modal-box">
    <div class="modal-header">
      Confirmar acción
      <span class="close" onclick="closeConfirmModal()">×</span>
    </div>
    <div class="modal-body">
      <p id="confirmText"></p>
      <button onclick="confirmAction()">Confirmar</button>
      <button onclick="closeConfirmModal()">Cancelar</button>
    </div>
  </div>
</div>
  
  
  
  
  
<!-- =========================================================
=                     JAVASCRIPT                            =
========================================================= -->
<script>
function abrirModal() {
    document.getElementById('miModalAjustes').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('miModalAjustes').style.display = 'none';
}

// Cerrar si hacen clic fuera de la cajita blanca
window.onclick = function(event) {
    var modal = document.getElementById('miModalAjustes');
    if (event.target == modal) {
        cerrarModal();
    }
}







let elementoAEliminar = { id: null, tipo: null };

function prepararEliminar(id, tipo) {
    elementoAEliminar = { id: id, tipo: tipo };
    confirmText.innerText = "¿Estás seguro de que deseas eliminar este elemento?";
    openModal("confirmModal");
}

function confirmAction() {
    if (elementoAEliminar.id) {
        $.ajax({
            url: "procesar.php",
            type: "POST",
            data: {
                action: "eliminar",
                id: elementoAEliminar.id,
                tipo: elementoAEliminar.tipo
            },
            success: function(res) {
                const respuesta = JSON.parse(res);
                if (respuesta.status === "ok") {
                    location.reload(); // Recarga para ver los cambios
                } else {
                    alert("Error: " + respuesta.msg);
                }
            }
        });
    }
    closeConfirmModal();
}







/* ================= MODALES ================= */
function openModal(id){ document.getElementById(id).style.display="flex"; }
function closeModal(id){ document.getElementById(id).style.display="none"; }

function openProfileModal(){ openModal("profileModal"); }
function closeProfileModal(){ closeModal("profileModal"); }

/* ================= MINI PERFIL ================= */
function openMiniProfile(id, img, title, text, url) {
  // 1. Llenar los datos del modal
  document.getElementById('modalImg').src = img;
  document.getElementById('modalTitle').innerText = title;
  document.getElementById('modalText').innerText = text;
  document.getElementById('modalLink').href = url;
  document.getElementById('modalLinkContainer').style.display = url ? "block" : "none";
  
  // 2. Configurar el botón de eliminar con el ID actual
  const btnEliminar = document.getElementById('btnEliminarServicio');
  btnEliminar.onclick = function() {
    // Cerramos el menú y el modal antes de proceder
    document.querySelectorAll(".menu-dropdown").forEach(m => m.style.display = "none");
    closeModal("modal");
    
    // Llamamos a tu función de eliminar pasando 'servicio' como tipo
    prepararEliminar(id, 'servicio');
  };
  
  openModal("modal");
}

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
//function confirmAction(){ closeConfirmModal(); }

/* ================= OTROS ================= */
function sharePage(){
  navigator.share
    ? navigator.share({title:"Perfil",url:location.href})
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








/* BUSCAR TELÉFONO */
$("#searchForm").submit(function(e) {
  e.preventDefault();

  let telefono = $("#telefonoBusqueda").val(); // Captura el valor del input

  $.ajax({
    url: "buscar_telefono.php",
    type: "POST",
    data: { telefono: telefono },
    success: function(res) {
      let alertBox = $("#searchAlert");

      if (res.trim() === "no_encontrado") {
        alertBox
          .removeClass()
          .addClass("alert-error")
          .text("Número no encontrado")
          .show();
      } else {
        // Redirigimos usando el parámetro 'numero' para que perfildecuentas.php lo reconozca
        window.location.href = "perfildecuentas.php?numero=" + telefono;
      }
    }
  });
});

</script>
  
 


</body>

<?php mysqli_close($conn); ?>
</html>