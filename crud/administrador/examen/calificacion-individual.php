<?php
session_start();
if (!isset($_SESSION['docentes'])) {
    echo '
    <script>
    alert ("Por favor, debes iniciar sesión");
    window.location = "../index.php";
    </script>';

    session_destroy();
    exit;
}

include '../../php/conexion_be.php';
// Obtiene el ID del usuario(o sea, el estudiante) actual
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

// Consulta para obtener la lista actualizada de exámenes
$query = "SELECT * FROM examenes";
$resultado = mysqli_query($conexion, $query);

// Consulta para obtener los aciertos de cada examen del estudiante
$query_aciertos = "SELECT examen_id, aciertos FROM usuarios_examenes WHERE estudiante_id = '$id'";
$resultado_aciertos = mysqli_query($conexion, $query_aciertos);

// Crear un array asociativo para almacenar los aciertos por examen
$aciertos_por_examen = array();
while ($row = mysqli_fetch_assoc($resultado_aciertos)) {
    $aciertos_por_examen[$row['examen_id']] = $row['aciertos'];
}

$query_preguntas = "SELECT pregunta FROM examen_preguntas";
$resultado_preguntas = mysqli_query($conexion, $query_preguntas);
$num_preguntas = mysqli_num_rows($resultado_preguntas);

// Obtén el nombre del estudiante desde la base de datos
$queryNombreEstudiante = "SELECT nombre FROM estudiantes WHERE id = '$id'";
$resultNombreEstudiante = mysqli_query($conexion, $queryNombreEstudiante);
$rowNombreEstudiante = mysqli_fetch_assoc($resultNombreEstudiante);
$nombreEstudiante = $rowNombreEstudiante['nombre'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    /* Estilo para el título h1 */
h1 {
    font-size: 24px;
    color: #333;
    text-align: center;
}

/* Estilo para el encabezado de la tabla */
thead th {
    background-color: #1c6a28;
    color: #fff;
    font-weight: bold;
}

/* Estilo para las celdas de la tabla */
table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ccc;
}

/* Estilo para las celdas de la tabla */
th, td {
    padding: 10px;
    text-align: center;
}

/* Estilo para las filas impares de la tabla */
tr:nth-child(odd) {
    background-color: #f2f2f2;
}
</style>
</head>
<body>
<h1>Nombre del Estudiante: <?php echo $nombreEstudiante; ?></h1>
<h1>(ID: <?php echo $id; ?>)</h1>
<table>
    <thead>
        <th>Nombre</th>
        <th>Aciertos</th>
        <th>Calificación</th>
        </thead>
<?php
if ($resultado->num_rows >= 1) {
            while ($examen = mysqli_fetch_assoc($resultado)) {
                $examen_id = $examen['id'];

                // Verificar si el estudiante ya ha realizado el examen
                $query_realizado = "SELECT examen_id FROM usuarios_examenes WHERE estudiante_id = '$id' AND examen_id = '$examen_id'";
                $resultado_realizado = mysqli_query($conexion, $query_realizado);

                if (mysqli_num_rows($resultado_realizado) > 0) {
                    $aciertos = isset($aciertos_por_examen[$examen_id]) ? $aciertos_por_examen[$examen_id] : 0;
                    $query_num_preguntas = "SELECT COUNT(*) as num_preguntas FROM examen_preguntas WHERE examen_id = $examen_id";
                    $resultado_num_preguntas = mysqli_query($conexion, $query_num_preguntas);
                    $row_num_preguntas = mysqli_fetch_assoc($resultado_num_preguntas);
                    $num_preguntas = $row_num_preguntas['num_preguntas'];
                    // El examen ya ha sido realizado
                    echo "<tr>";
                    echo "<td>{$examen['nombre']}</td>";
                    echo "<td class='t-center'>$aciertos/$num_preguntas</td>";
                    $cantidad_total = round(($aciertos * 100)/$num_preguntas);
                    echo "<td class='t-center'>$cantidad_total</td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr>";
            echo "<td colspan='3' class='no-registros'>No hay registros</td>";
            echo "</tr>";
        }
        ?>
</table>
<a href="editar-examen.php" class="boton-regresar">REGRESAR</a>
</body>
</html>
