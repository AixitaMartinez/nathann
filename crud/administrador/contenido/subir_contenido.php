<?php
session_start();
if (!isset($_SESSION['docentes'])) {
    echo '
    <script>
    alert("Por favor, debes iniciar sesión");
    window.location = "../../index.php";
    </script>';

    session_destroy();
    exit;
}

include '../../php/conexion_be.php';


if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Procesa el formulario de subida
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $tipo = $_POST["tipo"];
    $categoria = $_POST["categoria"];
    
    // Insertar información en la tabla de contenido
    $sql_contenido = "INSERT INTO contenido (titulo, descripcion, tipo, categoria_id) VALUES ('$titulo', '$descripcion', '$tipo', '$categoria')";
    if ($conexion->query($sql_contenido) === TRUE) {
        $id_contenido = $conexion->insert_id; // Obtiene el ID de la última entrada insertada

        // Itera a través de los archivos subidos y asócialos a la misma entrada
        foreach ($_FILES["archivos"]["name"] as $key => $nombre_archivo) {
            $archivo_temporal = $_FILES["archivos"]["tmp_name"][$key];
            $archivo_destino = "archivos/" . $nombre_archivo;
            move_uploaded_file($archivo_temporal, $archivo_destino);

            // Insertar información en la tabla de archivos y relacionarlos con la entrada de contenido
            $sql_archivos = "INSERT INTO archivos (id_contenido, nombre_archivo, ruta_archivo) VALUES ($id_contenido, '$nombre_archivo', '$archivo_destino')";
            if ($conexion->query($sql_archivos) !== TRUE) {
                echo "Error al insertar en la tabla de archivos: " . $conexion->error;
            }
        }
        echo '<script>
        alert("Almacenado con exito")
        window.location = "index_contenido.php";
        </script>';
        echo var_dump($archivo_destino);
    } else {
        echo "Error al insertar en la tabla de contenido: " . $conexion->error;
    }
}

$conexion->close();
?>
