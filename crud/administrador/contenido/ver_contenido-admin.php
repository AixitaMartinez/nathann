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
    background-color: beige;
    color: #1c6a28;
    padding: 10px;
    margin: 0;
}

.contenido-container {
    background-color: beige;
    border: 1px solid #ddd;
    border-radius: 10px; /* Aumentamos el radio del borde para hacerlo más redondeado */
    margin: 20px auto; /* Centramos el contenedor horizontalmente */
    padding: 15px;
    max-width: 600px;
}

p {
    margin: 0;
}

img {
    display: block; /* Para centrar la imagen horizontalmente */
    margin: 0 auto;
    max-width: 100%;
    height: auto; /* Usamos 'auto' para mantener la proporción */
    border-radius: 10px; /* Añadimos bordes redondeados a la imagen */
}

embed, video {
    display: block; /* Para centrar el video horizontalmente */
    margin: 0 auto;
    max-width: 100%; /* Cambiamos '70%' a '100%' para que ocupe todo el ancho disponible */
    height: 500px; /* Usamos 'auto' para mantener la proporción */
    border-radius: 10px; /* Añadimos bordes redondeados al video */
}

/* Estilos del menú lateral */
.menu__side {
    width: 250px;
    background-color: #333;
    color: #fff;
    position: fixed;
    top: 0;
    left: -250px;
    height: 100%;
    transition: left 0.3s;
    overflow-y: scroll;
    padding-top: 60px;
}

.icon__menu {
    cursor: pointer;
    text-align: left;
    margin-left: 10px;
    margin-top: 10px;
}

.icon__menu i {
    font-size: 30px;
    cursor: pointer;
}

.name__page {
    text-align: center;
    margin: 20px 0;
}

.name__page i {
    font-size: 30px;
}

.options__menu {
    padding: 0;
    list-style: none;
}

.option {
    padding: 10px;
    display: flex;
    align-items: center;
    color: #fff;
    text-decoration: none;
}

.option i {
    font-size: 20px;
    margin-right: 10px;
}

.option:hover {
    background-color: #444;
}

/* Estilos para los contenidos */
p {
    margin: 10px;
}

img {
    max-width: 50%;
    height: 200px;
}

embed {
    width: 50%;
    height: 400px;
}

video {
    width: 50%;
    height: 300px;
}

/* Estilos para enlaces */
a {
    text-decoration: none;
    color: inherit;
}

a.selected {
    background-color: #444;
}

a.selected .option i {
    color: #ffcc00;
}

    </style
</head>
<body>
    <header>
        <div class="icon__menu">
            <i class="fas fa-bars" id="btn_open"></i>
        </div>
    </header>

    <div class="menu__side" id="menu_side">
        <!-- ... (menú lateral) ... -->
    </div>

    <h1>Contenidos</h1>
    <?php
    if (isset($_GET["categoria_id"])) {
        $categoria_id = $_GET["categoria_id"];

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
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

                // Agregar enlaces para editar y eliminar
                echo "<a href='editar_contenido.php?id=$id_contenido'><i class='fa-solid fa-pen-to-square'></i>Editar</a> ";
                echo "<a href='eliminar_contenido.php?id=$id_contenido' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este contenido?\")'>Eliminar</a>";

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
