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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CMS - Administrador</title>
<link rel="stylesheet" href="../assets/css/index_contenido.css">
<script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>
</head>
<body id="body">
        
    <header>
        <div class="icon__menu">
            <i class="fas fa-bars" id="btn_open"></i>
        </div>
    </header>

<div class="menu__side" id="menu_side">

<div class="name__page">
    <i class="fa-solid fa-earth-americas"></i>
    <h4>Ciencias sociales</h4>
</div>

<div class="options__menu">	

    <a href="../Bienvenida_docente.php">
        <div class="option">
            <i class="fas fa-home" title="Inicio"></i>
            <h4>Inicio</h4>
        </div>
    </a>

    <a href="index_contenido.php" class="selected">
        <div class="option">
            <i class="far fa-file" title="Portafolio"></i>
            <h4>Material</h4>
        </div>
    </a>
    
    <a href="examen/editar-examen.php">
        <div class="option">
            <i class="fa-solid fa-bars-progress"></i>
            <h4>Simulacros</h4>
        </div>
    </a>
    <a href="ver_estudiantes.php">
        <div class="option">
        <i class="fa-solid fa-user-group"></i>
            <h4>Ver estudiantes</h4>
        </div>
    </a>
    <a href="actualizar_docentes.php">
        <div class="option">
            <i class="fa-solid fa-user"></i>
            <h4>Perfil</h4>
        </div>
    </a>

    <a href="../../php/cerrar_sesion.php">
        <div class="option">
        <i class="fa-solid fa-arrow-up-from-bracket fa-rotate-90"></i>
            <h4>cerrar sesión</h4>
        </div>
    </a>
</div>

</div>


    <form action="subir_contenido.php" method="POST" enctype="multipart/form-data">
    <h1>Subir Contenido Nuevo</h1>  
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" required><br>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required></textarea><br>
        <label for="tipo">Tipo:</label>
        <select name="tipo" required>
            <option value="imagen">Imagen</option>
            <option value="pdf">PDF</option>
            <option value="video">Video</option>
        </select><br>
        <label for="archivos">Archivos:</label>
        <input type="file" name="archivos[]" multiple required><br> <!-- Añade el atributo "multiple" -->
        <?php
        // Consulta para obtener las categorías disponibles desde la tabla de categorías
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $sql = "SELECT * FROM categorias";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el campo "Categoría" solo si hay categorías disponibles
            echo '<label for="categoria">Categoría:</label>';
            echo '<select name="categoria" required>';
            
            while ($row = $result->fetch_assoc()) {
                $categoria_id = $row["id"];
                $nombre_categoria = $row["nombre"];
                echo "<option value='$categoria_id'>$nombre_categoria</option>";
            }
            
            echo '</select>';
        }
        ?>
        <input type="submit" value="Enviar">
    </form>
    <div class="categorias">
        <h1>Crear Categorías</h1>
        <p>En este apartado, podrás crear las  categorías para cada contenido</p>

        <!-- Crear categoria -->
        <div class="boton-modal">
            <label for="btn-modal">
                Crear Categoría
            </label>
        </div>
            <!-- Ventana Modal -->
            <input type="checkbox" id="btn-modal">
            <div class="container-modal">
                <div class="content-modal">
                    <h3>Crear Categoría</h3>
                    <form action="crear_categoria.php" method="POST">
                        <p for="nombre_categoria">Nombre de la Categoría:</p>
                        <input type="text" name="nombre_categoria" required>
                        <div class="btn-cerrar">
                            <label for="btn-modal">Cerrar</label>
                        </div>
                        <input type="submit" value="Crear Categoría" class="guardar-modal">
                    </form>
                </div>
            
            </div>

            <h1>Categorías</h1>
    <ul>
        <?php

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $sql = "SELECT * FROM categorias";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categoria_id = $row["id"];
                $nombre_categoria = $row["nombre"];
                echo "<li><a href='ver_contenido-admin.php?categoria_id=$categoria_id'>$nombre_categoria</a></li>";
            }
        }
        $conexion->close();
        ?>
    </ul>


    </div>
    <a href="../assets/js/scripts.js">uwu</a>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>

</body>
</html>
