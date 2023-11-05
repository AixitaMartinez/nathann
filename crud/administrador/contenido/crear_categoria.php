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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_categoria = $_POST["nombre_categoria"];
    
    // Verificar
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Insertar la nueva categoría en la tabla de categorías
    $sql = "INSERT INTO categorias (nombre) VALUES ('$nombre_categoria')";

    if ($conexion->query($sql) === TRUE) {
        echo '<script>
            alert("Categoria creada con éxito");
            window.location = "index_contenido.php";
            </script>';
    } else {
        echo "Error al crear la categoría: " . $conexion->error;
    }

    $conexion->close();
}
?>
