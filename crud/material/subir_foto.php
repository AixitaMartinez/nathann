<?php
session_start(); // Asegúrate de haber iniciado la sesión

// Comprueba si se ha enviado un archivo
if ($_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    // Obtiene el ID del estudiante desde la sesión
    $estudiante_id = $_SESSION['estudiantes'];

    // Directorio donde se guardarán las imágenes
    $directorio_destino = 'fotos_perfil/';

    // Nombre original del archivo
    $nombre_original = $_FILES['foto_perfil']['name'];

    // Genera un nombre único para la imagen
    $nombre_archivo = 'perfil_' . $estudiante_id . '_' . time() . '.jpg'; // Puedes ajustar la extensión según el tipo de archivo

    // Ruta completa donde se guardará el archivo
    $ruta_destino = $directorio_destino . $nombre_archivo;

    // Incluir el archivo de conexión a la base de datos
    include('../php/conexion_be.php');

    // Verificar si la conexión se estableció correctamente
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Mueve la imagen al directorio destino
    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_destino)) {
        // Actualiza la base de datos con el nombre del archivo de imagen

        // Actualiza el campo 'foto_perfil' en la tabla 'perfil_estudiante'
        $sql = "UPDATE perfil_estudiante SET foto_perfil = '$nombre_archivo' WHERE estudiante_id = $estudiante_id";

        if (mysqli_query($conexion, $sql)) {
            echo "Imagen de perfil subida exitosamente.";
        } else {
            echo "Error al actualizar la imagen de perfil: " . mysqli_error($conexion);
        }

        // Cierra la conexión a la base de datos
        mysqli_close($conexion);
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "Error al procesar el archivo.";
}

// Obtén el ID del estudiante actual desde la sesión
$estudiante_id = $_SESSION['estudiantes'];

// Realiza una consulta para obtener el nombre del archivo de imagen de perfil
include('../php/conexion_be.php');

// Comprueba la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta para obtener el nombre del archivo de imagen de perfil
$sql = "SELECT foto_perfil FROM perfil_estudiante WHERE estudiante_id = $estudiante_id";

$resultado = mysqli_query($conexion, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $nombre_archivo = $fila['foto_perfil'];

    // Muestra la imagen de perfil si existe
    if ($nombre_archivo) {
        echo '<img src="fotos_perfil/' . $nombre_archivo . '" alt="Foto de Perfil">';
    } else {
        echo 'El estudiante no ha subido una foto de perfil.';
    }
} else {
    echo 'No se encontró un perfil para este estudiante.';
}

mysqli_close($conexion);
?>
