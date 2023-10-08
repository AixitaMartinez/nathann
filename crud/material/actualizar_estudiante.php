<?php
session_start();
if (!isset($_SESSION['estudiantes'])) {
    echo '
    <script>
    alert ("Por favor, debes iniciar sesión");
    window.location = "../index.php";
    </script>';

    session_destroy();
    exit;
}

include '../php/conexion_be.php';

// Obtener el ID del estudiante a actualizar ( POST)
$id_estudiante = $_SESSION['estudiantes'];

// Realizar una consulta SQL para obtener la información actual del estudiante
$query = "SELECT id, nombre, correo, contraseña,id_grado FROM estudiantes WHERE id = '$id_estudiante'";
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
    // Procesar el formulario de actualización y actualizar la información en la base de datos
    $nuevo_nombre = $_POST['nuevo_nombre'];
    $nuevo_id = $_POST['nuevo_id'];
    $nuevo_correo = $_POST['nuevo_correo'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $nuevo_grado = $_POST['nuevo_grado'];

    // Realizar una consulta SQL para actualizar la información del estudiante
    $query = "UPDATE estudiantes SET nombre = '$nuevo_nombre', id = '$nuevo_id', correo = '$nuevo_correo', contraseña = '$nueva_contrasena', id_grado = '$nuevo_grado' 
    WHERE id = '$id_estudiante'";
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


$id_grado = $row['id_grado'];
$queryg = "SELECT descripción FROM grados WHERE id = '$id_grado'";
$resultg = mysqli_query($conexion, $queryg); // Corrige el nombre de la variable aquí a $queryg
$rowg = mysqli_fetch_assoc($resultg);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Estudiante</title>
</head>
<body>
    <h2>Actualizar Información</h2>
    
    <!-- Mostrar la información actual del estudiante -->
    <p>Información actual:</p>
    <p>Nombre: <?php echo $row['nombre']; ?></p>
    <p>Tarjeta de Identidad: <?php echo $row['id']; ?></p>
    <p>Correo: <?php echo $row['correo']; ?></p>
    <p>Grado: <?php echo $rowg['descripción']; ?></p>
    <p>Contraseña: *********</p>

    <!-- Formulario para actualizar la información -->
    <form action="" method="POST">
        <label for="nuevo_nombre">Nuevo Nombre:</label>
        <input type="text" name="nuevo_nombre" required>
        <br>
        <label for="nuevo_id">Nueva Tarjeta de Identidad:</label>
        <input type="text" name="nuevo_id" required>
        <br>
        <label for="nuevo_correo">Nuevo Correo:</label>
        <input type="email" name="nuevo_correo" required>
        <label for="nuevo_correo">Nuevo Grado:</label>
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
</body>
<a href="bienvenida.php">Regresar</a>
</html>
