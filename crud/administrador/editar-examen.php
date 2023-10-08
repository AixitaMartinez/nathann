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

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

// REDIRECCIONAR SI NO SE ENCUENTRA EL EXAMEN
if (!$id) {
    header('location: editar-examen.php');
    exit;
}

$query = "SELECT * FROM examenes WHERE id = $id";
$resultado = mysqli_query($conexion, $query);
$examen = mysqli_fetch_assoc($resultado);

// Initialize $num_preguntas
$num_preguntas = 0;

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre']; // Nombre del examen

    // Actualizar el nombre del examen en la base de datos
    $query = "UPDATE examenes SET nombre = '$nombre' WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        // Actualizar preguntas, incisos y respuestas
        $num_preguntas = isset($_POST['num_preguntas']) ? intval($_POST['num_preguntas']) : 0; // Obtener el número de preguntas

        for ($i = 1; $i <= $num_preguntas; $i++) {
            $pregunta = $_POST["preg$i"]; // Pregunta
            $correcta = $_POST["preg{$i}_correcta"]; // Opción correcta

            // Actualizar pregunta en la tabla examen_preguntas
            $query = "UPDATE examen_preguntas SET pregunta = '$pregunta', correcta = '$correcta' WHERE examen_id = $id AND orden = $i";
            $resultado = mysqli_query($conexion, $query);

            if ($resultado) {
                // Actualizar incisos y respuestas
                for ($letra = 'a'; $letra <= 'd'; $letra++) {
                    $opcion = $_POST["preg{$i}_opcion_{$letra}"]; // Opción (inciso)

                    // Actualizar opción en la tabla respuestas
                    $query = "UPDATE respuestas SET respuesta = '$opcion' WHERE examen_id = $id AND pregunta_orden = $i AND inciso = '$letra'";
                    $resultado = mysqli_query($conexion, $query);
                }
            }
        }

        echo "Examen actualizado correctamente.";
        // Puedes redirigir al usuario a una página de confirmación o a la lista de exámenes
        // header('location: lista-examenes.php');
    } else {
        echo "Error al actualizar el examen.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Examen</title>
</head>
<body>
    <h2>Editar Examen</h2>
    <form method="POST" action="">
        <label for="nombre">Nombre del Examen:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo $examen['nombre']; ?>">
        <hr>
        <p class="intrucciones-examen">Editar preguntas, incisos y respuestas:</p>
        <input type="hidden" name="num_preguntas" id="num_preguntas" value="<?php echo isset($examen['numero_preguntas']) ? $examen['numero_preguntas'] : 0; ?>">

        <div id="preguntas-container">
            <!-- Contenedor de preguntas -->
            <?php for ($i = 1; $i <= $num_preguntas; $i++) { ?>
                <div class="pregunta-container">
                    <label for="preg<?php echo $i; ?>">Pregunta <?php echo $i; ?>:</label>
                    <input type="text" name="preg<?php echo $i; ?>" id="preg<?php echo $i; ?>" value="<?php echo obtenerPregunta($conexion, $id, $i); ?>">

                    <label for="preg<?php echo $i; ?>_correcta">Opción correcta:</label>
                    <select name="preg<?php echo $i; ?>_correcta" id="preg<?php echo $i; ?>_correcta">
                        <option value="a" <?php if (obtenerCorrecta($conexion, $id, $i) === 'a') echo 'selected'; ?>>A</option>
                        <option value="b" <?php if (obtenerCorrecta($conexion, $id, $i) === 'b') echo 'selected'; ?>>B</option>
                        <option value="c" <?php if (obtenerCorrecta($conexion, $id, $i) === 'c') echo 'selected'; ?>>C</option>
                        <option value="d" <?php if (obtenerCorrecta($conexion, $id, $i) === 'd') echo 'selected'; ?>>D</option>
                    </select>

                    <div class="opciones-container">
                        <?php for ($letra = 'a'; $letra <= 'd'; $letra++) { ?>
                            <label for="preg<?php echo $i; ?>_opcion_<?php echo $letra; ?>">Opción <?php echo $letra; ?>:</label>
                            <input type="text" name="preg<?php echo $i; ?>_opcion_<?php echo $letra; ?>" id="preg<?php echo $i; ?>_opcion_<?php echo $letra; ?>" value="<?php echo obtenerOpcion($conexion, $id, $i, $letra); ?>">
                        <?php } ?>
                    </div>
                </div>
                <hr>
            <?php } ?>
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>
<?php
if (isset($_POST['num_preguntas'])) {
    $num_preguntas = intval($_POST['num_preguntas']); // Obtener el número de preguntas
    // Resto de tu código aquí
} else {
    // Manejar el caso en el que 'num_preguntas' no está presente en la solicitud POST
    echo "Error: El campo 'num_preguntas' no está presente en la solicitud POST.";
}

function obtenerPregunta($conexion, $examenId, $orden) {
    $query = "SELECT pregunta FROM examen_preguntas WHERE examen_id = $examenId AND orden = $orden";
    $resultado = mysqli_query($conexion, $query);
    $row = mysqli_fetch_assoc($resultado);
    return $row['pregunta'];
}

function obtenerCorrecta($conexion, $examenId, $orden) {
    $query = "SELECT correcta FROM examen_preguntas WHERE examen_id = $examenId AND orden = $orden";
    $resultado = mysqli_query($conexion, $query);
    $row = mysqli_fetch_assoc($resultado);
    return $row['correcta'];
}

function obtenerOpcion($conexion, $examenId, $orden, $inciso) {
    $query = "SELECT respuesta FROM respuestas WHERE examen_id = $examenId AND pregunta_orden = $orden AND inciso = '$inciso'";
    $resultado = mysqli_query($conexion, $query);
    $row = mysqli_fetch_assoc($resultado);
    return $row['respuesta'];
}
?>
