<?php
session_start();
if (!isset($_SESSION['docentes'])) {
    // Redirige a la página de inicio de sesión si no está autenticado.
    header('Location: ../../index.php');
    exit;
}
include '../../php/conexion_be.php';

if (isset($_GET['id'])) {
    $id_contenido = $_GET['id'];

    // Primero, elimina los registros de archivos relacionados
    $sql_eliminar_archivos = "DELETE FROM archivos WHERE id_contenido = $id_contenido";
    if ($conexion->query($sql_eliminar_archivos) === TRUE) {
        // Luego, elimina el contenido
        $sql_eliminar_contenido = "DELETE FROM contenido WHERE id = $id_contenido";
        if ($conexion->query($sql_eliminar_contenido) === TRUE) {
            // Redirige de nuevo a la página de contenidos después de eliminar.
            header('ver-contenido-admin.php');
            echo 'listo';
            exit;
        } else {
            // Hubo un error al eliminar el contenido.
            echo "Error al eliminar el contenido: " . $conexion->error;
        }
    } else {
        // Hubo un error al eliminar los archivos.
        echo "Error al eliminar los archivos relacionados: " . $conexion->error;
    }
} else {
    // No se proporcionó un ID válido.
    echo "ID de contenido no válido.";
}

$conexion->close();
?>
