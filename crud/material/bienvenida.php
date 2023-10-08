<?php
session_start();
if (!isset($_SESSION['estudiantes'])) {
    echo '
    <script>
    alert ("Por favor, debes inicar sesión");
    window.location = "../index.php";
    </script>';

    session_destroy();
    exit;
    
}
include '../php/conexion_be.php';

// Obtener el ID del estudiante a actualizar ( POST)
$id_estudiante = $_SESSION['estudiantes'];
$query = "SELECT nombre FROM estudiantes WHERE id = '$id_estudiante'";
$result = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h4>holanda,  <?php echo $row['nombre']; ?></h4>
    <a href="../php/cerrar_sesion.php">Cerrar sesión</a>
    <a href="actualizar_estudiante.php">Actualizar información</a>
</body>
</html>