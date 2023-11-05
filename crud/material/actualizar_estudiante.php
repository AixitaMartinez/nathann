<?php
session_start();
if (!isset($_SESSION['estudiantes'])) {
    echo '
    <script>
    alert("Por favor, debes iniciar sesión");
    window.location = "../index.php";
    </script>';

    session_destroy();
    exit;
}

include '../php/conexion_be.php';

// Obtener el ID del estudiante a actualizar SESSION
$id_estudiante = $_SESSION['estudiantes'];

// Realizar una consulta SQL para obtener la información actual del estudiante
$query = "SELECT id, nombre, correo, contraseña, id_grado FROM estudiantes WHERE id = '$id_estudiante'";
$result = mysqli_query($conexion, $query);

//Si el resultado es nulo o las filas estan vacias se cierra la sesión
if (!$result || mysqli_num_rows($result) === 0) {
    echo '<script>
        alert("Error al obtener la información del estudiante");
        window.location = "ver_estudiantes.php";
    </script>';
    session_destroy();
    exit;
}

$row = mysqli_fetch_assoc($result);

//Eliminar cuenta
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['eliminar_cuenta'])) {
        // Eliminar la cuenta del estudiante
        $query = "DELETE FROM multimedia WHERE estudiante_id = '$id_estudiante'";
        $resultado_delete = mysqli_query($conexion, $query);

        $query = "DELETE FROM usuarios_examenes WHERE estudiante_id = '$id_estudiante'";
        $resultado_delete = mysqli_query($conexion, $query);
    
        $query = "DELETE FROM estudiantes WHERE id = '$id_estudiante'";
        $resultado_delete = mysqli_query($conexion, $query);


        if ($resultado_delete) {
            // Redireccionar a la página de inicio o mostrar un mensaje de éxito
            echo '<script>
                alert("Cuenta eliminada exitosamente");
                window.location = "../index.php";
            </script>';
            session_destroy();
            exit;
        } else {
            echo '<script>
                alert("Error al eliminar la cuenta");
                window.location = "bienvenida.php";
            </script>';
        }
    } else {
        // Procesar el formulario de actualización y actualizar la información en la base de datos
        $nuevo_nombre = $_POST['nuevo_nombre'];
        $nuevo_id = $_POST['nuevo_id'];
        $nuevo_correo = $_POST['nuevo_correo'];
        $contrasena = $_POST['nueva_contrasena'];
        $nuevo_grado = $_POST['nuevo_grado'];
        $nueva_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        // Realizar una consulta SQL para actualizar la información del estudiante
        $query_update = "UPDATE estudiantes SET nombre = '$nuevo_nombre', id = '$nuevo_id', correo = '$nuevo_correo', contraseña = '$nueva_contrasena', id_grado = '$nuevo_grado' 
        WHERE id = '$id_estudiante'";
        $result_update = mysqli_query($conexion, $query_update);

        if ($result_update) {
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
}
// Obtener información del grado
    $id_grado = $row['id_grado'];
    $queryg = "SELECT descripción FROM grados WHERE id = '$id_grado'";
    $resultg = mysqli_query($conexion, $queryg);
    $rowg = mysqli_fetch_assoc($resultg);

    //Foto de perfil
    $foto_perfil = "SELECT imagen FROM multimedia WHERE estudiante_id ={$id_estudiante}";
    $resultado_perfil = mysqli_query($conexion, $foto_perfil);

    $nombre_imagen = mysqli_fetch_assoc($resultado_perfil);
    $cantidad_imagen = mysqli_num_rows($resultado_perfil);

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/actualizar_estudiante.css">
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

            <a href="bienvenida.php" >
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

            <a href="actualizar_estudiante.php" class="selected">
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
             <!-- Foto de perfil -->
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

        <!-- Formulario para actualizar la información -->
        <form action="" method="POST">
            <h2>Información personal</h2>
            <label for="nuevo_nombre">Nombre:</label>
            <input type="text" name="nuevo_nombre" value=" <?php echo $row['nombre']; ?>"required>
            <br>
            <label for="nuevo_id">Tarjeta de Identidad:</label>
            <input type="text" name="nuevo_id" value="<?php echo $row['id']; ?>" required>
            <br>
            <label for="nuevo_correo">Correo:</label>
            <input type="email" name="nuevo_correo" value="<?php echo $row['correo']; ?>" required> <br>

            <label for="nuevo_grado">Grado:</label>
            <select  name="nuevo_grado">
                <option value="1">Undécimo</option>
                <option value="2">Décimo</option>
                <option value="3">Noveno</option>
            </select>
            <br>
            <label for="nueva_contrasena">Contraseña:</label>
            <input type="password" name="nueva_contrasena" required>
            <br>
            <br>
            <input type="submit" value="Actualizar">
        </form>
        <!-- Agrega un botón o enlace para eliminar la cuenta -->
        <form action="" method="POST">
            <input type="hidden" name="eliminar_cuenta" value="1">
            <input type="submit" value="Eliminar Cuenta" onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta?');">
        </form>
    </div>
    </main>
    <script src="assets/js/script.js"></script>
</body>
</html>