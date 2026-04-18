<?php
include "db.php";

if (isset($_POST['telefono'])) {
    // Escapamos el dato para evitar Inyecciones SQL básicas
    // Nota: $conn debe estar definida en db.php usando mysqli_connect
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);

    // Construimos la consulta directamente
    $sql = "SELECT id FROM usuarios WHERE numero = '$telefono'";
    
    // Ejecutamos la consulta de forma procedimental
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Obtenemos la fila como un array asociativo
        $user = mysqli_fetch_assoc($result);
        
        // Devolvemos el 'id'
        echo $user['id']; 
    } else {
        echo "no_encontrado";
    }

    // Opcional: liberar el resultado
    mysqli_free_result($result);
}
?>