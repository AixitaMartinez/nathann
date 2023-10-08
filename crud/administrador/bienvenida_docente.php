<?php
session_start();
if (!isset($_SESSION['docentes'])) {
    echo '
    <script>
    alert("Por favor, debes iniciar sesión");
    window.location = "../index.php";
    </script>';

    session_destroy();
    exit;
}

include '../php/conexion_be.php';
// Obtener el ID del docente
$id_docente = $_SESSION['docentes'];
$query = "SELECT nombre FROM docentes WHERE id_cedula = '$id_docente'";
$result = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($result);

/*
if (!$result) {
    echo 'Error en la consulta: ' . mysqli_error($conexion); // Muestra el error SQL
    exit;
}
*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Bienvenido, docente <?php echo $row['nombre']?></p>
    <a href="../php/cerrar_sesion.php">Cerrar sesión</a>
    <a href="ver_estudiantes.php">Ver datos</a>
</body>
</html>
