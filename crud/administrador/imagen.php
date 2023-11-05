<?php
include '../php/conexion_be.php';
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


// Obtiene el ID del usuario (es decir, el docente) actual
$user_id = intval($_SESSION['docentes']);

    $nombreimg = $_FILES["imagen"]['name']; // Obtiene el nombre de la imagen
    $archivo = $_FILES['imagen']['tmp_name']; // Obtiene el archivo

    $ruta = "imagenes/" . $nombreimg;

    move_uploaded_file($archivo, $ruta);

    // Elimina la imagen de perfil anterior si existe
    $eliminar_imagen_anterior = "DELETE FROM multimedia_doc WHERE docente_id = $user_id";
    $resultado_eliminar_anterior = mysqli_query($conexion, $eliminar_imagen_anterior);

    $sql = "INSERT INTO multimedia_doc(imagen, docente_id) VALUES('$nombreimg', $user_id)";
    $query = mysqli_query($conexion, $sql);

    if ($query) {
        echo "Insertado correctamente" . "<br>";
        echo "<a href='actualizar_docentes.php'>Atrás</a>";
        $_SESSION['ruta'] = $ruta;
    } else {
        echo "No se insertó la imagen <br>";
    }
?>
