<?php
session_start();
if (!isset($_SESSION['docentes'])) {
    // Redirige a la página de inicio de sesión si no está autenticado.
    header('Location: ../../index.php');
    exit;
}
include '../../php/conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_contenido = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    // Actualiza los datos del contenido en la base de datos.
    $sql = "UPDATE contenido SET titulo = '$titulo', descripcion = '$descripcion' WHERE id = $id_contenido";
    if ($conexion->query($sql) === TRUE) {
        // Redirige de nuevo a la página de contenidos después de actualizar.
        header('Location: ver_contenido-admin.php');
        exit;
    } else {
        // Hubo un error al actualizar los datos.
        echo "Error al actualizar el contenido: " . $conexion->error;
    }
}

$conexion->close();
?>
