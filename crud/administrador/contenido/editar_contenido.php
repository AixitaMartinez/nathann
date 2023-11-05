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

    // Obtén los datos del contenido a editar desde la base de datos.
    $sql = "SELECT * FROM contenido WHERE id = $id_contenido";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $titulo = $row['titulo'];
        $descripcion = $row['descripcion'];
        $tipo = $row['tipo'];

        // Aquí puedes mostrar un formulario con los datos actuales y permitir la edición.
        // Por ejemplo, un formulario HTML que permita editar el título y la descripción.

        echo "<form action='actualizar_contenido.php' method='post'>";
        echo "<input type='hidden' name='id' value='$id_contenido'>";
        echo "Título: <input type='text' name='titulo' value='$titulo'><br>";
        echo "Descripción: <input type='text' name='descripcion' value='$descripcion'><br>";
        echo "<input type='submit' value='Guardar cambios'>";
        echo "</form>";
    } else {
        // El contenido no existe.
        echo "El contenido no existe.";
    }
} else {
    // No se proporcionó un ID válido.
    echo "ID de contenido no válido.";
}

$conexion->close();
?>
