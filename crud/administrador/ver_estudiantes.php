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
$queryy = "SELECT * FROM estudiantes";
$result = mysqli_query($conexion, $queryy);
//eliminar estudiante

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];

    $query = "DELETE FROM multimedia WHERE estudiante_id = {$id}";
    $resultado = mysqli_query($conexion, $query);

    $query = "DELETE FROM usuarios_examenes WHERE estudiante_id = {$id}";
    $resultado = mysqli_query($conexion, $query);
    
    $query = "DELETE FROM estudiantes WHERE id = {$id}";
    $resultado = mysqli_query($conexion, $query);

   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
    <link rel="stylesheet" href="assets/css/ver_es.css">
    <script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>
    <script>
    function confirmarEliminacion(id) {
        var confirmacion = confirm("¿Estás seguro de que deseas eliminar este estudiante?");
        if (confirmacion) {
            document.getElementById("eliminarRegistro_" + id).submit();
        }
    }
</script>
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
            <th>Acciones</th>
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
                <td> <form action="" method="POST" id="eliminarRegistro_<?php echo $row['id']; ?>">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <a href="#" onclick="confirmarEliminacion(<?php echo $row['id']; ?>)">
                    <i class="fa-solid fa-trash"></i>
                    </a>
                    </td>
           </form>
           
        <?php } ?>
        
    </table>

    <a href="bienvenida_docente.php" class="boton-regresar">REGRESAR</a>
    </div>

    
</body>
</html>