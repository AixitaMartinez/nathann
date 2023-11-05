<?php
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

include '../php/conexion_be.php';

// Obtener el ID del docente a actualizar ( POST)
$id_docente = $_SESSION['docentes'];

// Realizar una consulta SQL para obtener la información actual del docente
$query = "SELECT id_cedula, nombre, correo, contraseña FROM docentes WHERE id_cedula = '$id_docente'";
$result = mysqli_query($conexion, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo '<script>
        alert("Error al obtener la información del docente");
        window.location = "bienvenida_docente.php";
    </script>';
    exit;
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procesar el formulario de actualización y actualizar la información en la base de datos
    $nuevo_nombre = $_POST['nuevo_nombre'];
    $nuevo_id = $_POST['nuevo_id'];
    $nuevo_correo = $_POST['nuevo_correo'];
    $nueva_contrasena = $_POST['nueva_contrasena'];

    // Realizar una consulta SQL para actualizar la información del docente
    $query = "UPDATE docentes SET nombre = '$nuevo_nombre', id_cedula = '$nuevo_id', correo = '$nuevo_correo', contraseña = '$nueva_contrasena' 
    WHERE id = '$id_docente'";
    $result = mysqli_query($conexion, $query);

    if ($result) {
        echo '<script>
            alert("Información actualizada exitosamente");
            window.location = "bienvenida.php";
        </script>';
    } else {
        echo '<script>
            alert("Error al actualizar la información");
            window.location = "bienvenida.php";
        </script>';
    }
}
 //Foto de perfil
 $foto_perfil = "SELECT imagen FROM multimedia_doc WHERE docente_id ={$id_docente}";
 $resultado_perfil = mysqli_query($conexion, $foto_perfil);

 $nombre_imagen = mysqli_fetch_assoc($resultado_perfil);
 $cantidad_imagen = mysqli_num_rows($resultado_perfil);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/actualizar_doc.css">
    <title>Perfil</title>
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

        <a href="Bienvenida_docente.php">
                <div class="option">
                    <i class="fas fa-home" title="Inicio"></i>
                    <h4>Inicio</h4>
                </div>
            </a>

            <a href="#">
                <div class="option">
                    <i class="far fa-file" title="Portafolio"></i>
                    <h4>Material</h4>
                </div>
            </a>
            
            <a href="#">
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
            <a href="actualizar_docentes.php" class="selected">
                <div class="option">
                    <i class="fa-solid fa-user"></i>
                    <h4>Perfil</h4>
                </div>
            </a>

            <a href="../php/cerrar_sesion.php">
                <div class="option">
                    <i class="fa-regular fa-circle-xmark"></i>
                    <h4>cerrar sesión</h4>
                </div>
            </a>
        </div>

    </div>

    <main>
    <div class="container">
    <div class="boton-modal">
            <label for="btn-modal2">
            <?php 
                if ($cantidad_imagen > 0) {
                    echo '<img src="imagenes/' . $nombre_imagen['imagen'] . '" width="200px" height="200px" class="foto-perfil" title="Cambiar foto de perfil">';
                } else {
                    echo '<img src="imagenes/foto.png" class="foto-perfil">';
                }
            ?>
            </label>
        </div>
        <input type="checkbox" id="btn-modal2">
        <div class="container-modal2">
            <div class="content-modal">
            <h3>Cambiar Foto de Perfil</h3>
            <form action="imagen.php" method="post" enctype="multipart/form-data">
                <p>
                    <input type="file" name="imagen">
                </p>
                <div class="btn-cerrar">
                    <label for="btn-modal2">Cerrar</label>
                    <input type="submit" name="guardar" value="Guardar" class="guardar-modal">
                </div>
            </form>
        </div>
    
        <label for="btn-modal2" class="cerrar-modal"></label>
        </div>

    <form action="" method="POST">
        <label for="nuevo_nombre">Nuevo Nombre:</label>
        <input type="text" name="nuevo_nombre" value="<?php echo $row['nombre']; ?>" required>
        <br>
        <label for="nuevo_id">Cedula:</label>
        <input type="text" name="nuevo_id" value="<?php echo $row['id_cedula']; ?>" required>
        <br>
        <label for="nuevo_correo">Nuevo Correo:</label>
        <input type="email" name="nuevo_correo" value="<?php echo $row['correo']; ?>" required>
        <br>
        <label for="nueva_contrasena">Nueva Contraseña:</label>
        <input type="password" name="nueva_contrasena" required>
        <br>
        <br>
        <input type="submit" value="Actualizar">
    </form>
    </div>
  
    </main>
    <script src="../material/assets/js/script.js"></script>
</body>
</html>
