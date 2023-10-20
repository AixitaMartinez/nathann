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

// Obtener el ID del estudiante a actualizar ( POST)
$id_estudiante = $_SESSION['estudiantes'];

// Realizar una consulta SQL para obtener la información actual del estudiante
$query = "SELECT id, nombre, correo, contraseña, id_grado FROM estudiantes WHERE id = '$id_estudiante'";
$result = mysqli_query($conexion, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo '<script>
        alert("Error al obtener la información del estudiante");
        window.location = "ver_estudiantes.php";
    </script>';
    exit;
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['eliminar_cuenta'])) {
        // Eliminar la cuenta del estudiante
        $query_delete = "DELETE FROM estudiantes WHERE id = '$id_estudiante'";
        $result_delete = mysqli_query($conexion, $query_delete);

        if ($result_delete) {
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
        $nueva_contrasena = $_POST['nueva_contrasena'];
        $nuevo_grado = $_POST['nuevo_grado'];

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

$id_grado = $row['id_grado'];
$queryg = "SELECT descripción FROM grados WHERE id = '$id_grado'";
$resultg = mysqli_query($conexion, $queryg);
$rowg = mysqli_fetch_assoc($resultg);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Perfil</title>
    <script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>
    <style>
/* Estilos para el formulario */
form {
    margin-top: 10px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="password"],
select {
    width: 25%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: green;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #444;
}

/* Estilos para los mensajes de información */
p {
    margin-top: 10px;
}

/* Estilos para el botón de eliminar cuenta */
form[action=""] {
    margin-top: 20px;
}

/* Estilos para el pie de página */
footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px;
    position: absolute;
    bottom: 0;
    width: 100%;
}
    </style>
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


    
        <!-- Mostrar la información actual del estudiante -->
        <p>Información actual:</p>
        <p>Nombre: <?php echo $row['nombre']; ?></p>
        <p>Tarjeta de Identidad: <?php echo $row['id']; ?></p>
        <p>Correo: <?php echo $row['correo']; ?></p>
        <p>Grado: <?php echo $rowg['descripción']; ?></p>
        <p>Contraseña: *</p> <br>
        <h2>Actualizar Información</h2> 
        
        <!-- Formulario para actualizar la información -->

        <form action="" method="POST">
        <label for="nuevo_nombre">Nuevo Nombre:</label>
        <input type="text" name="nuevo_nombre" value=" <?php echo $row['nombre']; ?>"required>
        <br>
        <label for="nuevo_id">Nueva Tarjeta de Identidad:</label>
        <input type="text" name="nuevo_id" value="<?php echo $row['id']; ?>" required>
        <br>
        <label for="nuevo_correo">Nuevo Correo:</label>
        <input type="email" name="nuevo_correo" value="<?php echo $row['correo']; ?>" required>

        <label for="nuevo_grado">Nuevo Grado:</label>
        <select  name="nuevo_grado">
                        <option value="1">Undécimo</option>
                        <option value="2">Décimo</option>
                        <option value="3">Noveno</option>
                    </select>
        <br>
        <label for="nueva_contrasena">Nueva Contraseña:</label>
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
    </main>
    <script src="assets/js/script.js"></script>
</body>
</html>