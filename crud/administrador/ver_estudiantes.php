<?php
session_start();
if (!isset($_SESSION['docentes'])) {
    echo '
    <script>
    alert ("Por favor, debes inicar sesión");
    window.location = "../index.php";
    </script>';

    session_destroy();
    exit;
}

include '../php/conexion_be.php';

// Realizar una consulta SQL para obtener los datos de los estudiantes
$query = "SELECT * FROM estudiantes";
$result = mysqli_query($conexion, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
    <link rel="stylesheet" href="assets/css/ver_es.css">
</head>
<body>
    <div class="contenedor">
    <h2>Lista de Estudiantes</h2>
    <table>
        <tr>
            <th>Ti/CC</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Grado</th>
       
            <!-- Agrega más encabezados de columnas según tus necesidades -->
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['correo']; ?></td>
                <?php
                // Obtener la descripción del grado para este estudiante
                $id_grado = $row['id_grado'];
                $queryg = "SELECT descripción FROM grados WHERE id = '$id_grado'";
                $resultg = mysqli_query($conexion, $queryg);
                $rowg = mysqli_fetch_assoc($resultg);
                ?>
                <td><?php echo $rowg['descripción']; ?></td>
                <!-- Agrega más celdas de datos según tus necesidades -->
            </tr>
        <?php } ?>
    </table>

    <a href="bienvenida_docente.php" class="boton-regresar">REGRESAR</a>
    </div>

    
</body>
</html>