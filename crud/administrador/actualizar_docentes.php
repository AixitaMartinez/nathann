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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../material/assets/css/styles.css">
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
    <h2>Actualizar Información</h2>
    
    <!-- Mostrar la información actual del docente -->
    <p>Información actual:</p>
    <p>Nombre: <?php echo $row['nombre']; ?></p>
    <p>Cedula de ciudadanía: <?php echo $row['id_cedula']; ?></p>
    <p>Correo: <?php echo $row['correo']; ?></p>

    <p>Contraseña: *********</p>

    <!-- Formulario para actualizar la información -->
    <form action="" method="POST">
        <label for="nuevo_nombre">Nuevo Nombre:</label>
        <input type="text" name="nuevo_nombre" value="<?php echo $row['nombre']; ?>" required>
        <br>
        <label for="nuevo_id">Cedula:</label>
        <input type="text" name="nuevo_id" value="<?php echo $row['id_cedula']; ?>" required>
        <br>
        <label for="nuevo_correo">Nuevo Correo:</label>
        <input type="email" name="nuevo_correo" value="<?php echo $row['correo']; ?>" required>
       
        <label for="nueva_contrasena">Nueva Contraseña:</label>
        <input type="password" name="nueva_contrasena" required>
        <br>
        <br>
        <input type="submit" value="Actualizar">
    </form>
    </main>
    <script src="../material/assets/js/script.js"></script>
</body>
</html>
