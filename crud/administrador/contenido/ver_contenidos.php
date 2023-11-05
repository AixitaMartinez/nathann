<?php
session_start();
if (!isset($_SESSION['estudiantes'])) {
    echo '
    <script>
    alert("Por favor, debes iniciar sesión");
    window.location = "../../index.php";
    </script>';

    session_destroy();
    exit;
}
include '../../php/conexion_be.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CMS - Contenidos</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }

    h1 {
        text-align: center;
        background-color: #0077b6;
        color: white;
        padding: 10px;
        margin: 0;
    }

    .contenido-container {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 20px;
        padding: 15px;
        width: 0 auto;
        width: 800px;
        max-width: 80%;
    }

    p {
        margin: 0;
    }

    img {
        max-width: 100%;
        height: auto;
    }

    embed, video {
        max-width: 100%;
    }
</style>

</head>
<body>
    <h1>Contenidos</h1>
    <?php
    if (isset($_GET["categoria_id"])) {
        $categoria_id = $_GET["categoria_id"];
      
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM contenido WHERE categoria_id = $categoria_id";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $titulo = $row["titulo"];
                $tipo = $row["tipo"];
                $descripcion = $row["descripcion"];
                $id_contenido = $row["id"];
                echo "<div class='contenido-container'>";
                // Mostrar la información del contenido
                echo "<p><strong>$titulo</strong> - $descripcion</p>";
                 // Consulta para obtener los archivos asociados a esta entrada
                 $sql_archivos = "SELECT * FROM archivos WHERE id_contenido = $id_contenido";
                 $result_archivos = $conexion->query($sql_archivos);
                //mostrar imagenes/pdf/video
                 if ($result_archivos->num_rows > 0) {
                     while ($row_archivo = $result_archivos->fetch_assoc()) {
                         $nombre_archivo = $row_archivo["nombre_archivo"];
                         $ruta_archivo = $row_archivo["ruta_archivo"];
                            
                         if ($tipo === "imagen") {
                             echo "<img src='$ruta_archivo' alt='$nombre_archivo' width='200px' height='200px'><br>";
                         } elseif ($tipo === "pdf") {
                             echo "<embed src='$ruta_archivo' type='application/pdf' width='600' height='400'><br>";
                         } elseif ($tipo === "video") {
                             echo "<video controls><source src='$ruta_archivo' type='video/mp4'></video><br>";
                         }
                     }
                 }
                 echo "</div>";
            }
        } else {
            echo "No hay contenidos disponibles en esta categoría.";
        }
        
        $conexion->close();
    } else {
        echo "No se ha especificado una categoría.";
    }
    ?>
</body>
</html>
