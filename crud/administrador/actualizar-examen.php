<?php
include '../php/conexion_be.php';

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

// Consultar id del examen
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

$query = "SELECT * FROM examen_preguntas WHERE examen_id = {$id}";
$resultado = mysqli_query($conexion, $query);

// Crear un array para almacenar todas las preguntas y respuestas
$preguntas_respuestas = [];

while ($preguntas = mysqli_fetch_assoc($resultado)) {
    $preguntaID = $preguntas['id'];
    $query_respuestas = "SELECT * FROM respuestas WHERE pregunta_id = {$preguntaID}";
    $resultado_respuestas = mysqli_query($conexion, $query_respuestas);

    // Crear un array de respuestas para esta pregunta
    $respuestas_pregunta = [];

    while ($respuestas = mysqli_fetch_assoc($resultado_respuestas)) {
        $respuestas_pregunta[] = $respuestas['respuesta'];
    }

    // Agregar la pregunta y sus respuestas al array
    $preguntas_respuestas[] = [
        'pregunta' => $preguntas,
        'respuestas' => $respuestas_pregunta,
    ];
}

// Verificar si el formulario se ha enviado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre']; // Nombre del examen
    // Actualiza el nombre del examen en la base de datos
    $query = "UPDATE examenes SET nombre = '$nombre' WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo "Examen actualizado correctamente.";
        // Aquí puedes redirigir al usuario o realizar otras acciones después de la actualización.
    } else {
        echo "Error al actualizar el examen.";
    }

    // Actualiza las preguntas y respuestas
    foreach ($_POST['pregunta'] as $indice => $pregunta) {
        $preguntaID = $preguntas_respuestas[$indice]['pregunta']['id'];
        // Actualiza la pregunta en la base de datos
        $query = "UPDATE examen_preguntas SET pregunta = '$pregunta' WHERE id = $preguntaID";
        $resultado = mysqli_query($conexion, $query);

        if (!$resultado) {
            echo "Error al actualizar la pregunta $preguntaID.";
        }

        // Actualiza las respuestas
        foreach ($_POST['respuesta'][$indice] as $respuestaIndice => $respuesta) {
            $respuestaID = $preguntas_respuestas[$indice]['respuestas'][$respuestaIndice]['id'];
          
            // Actualiza la respuesta en la base de datos
            $query = "UPDATE respuestas SET respuesta = '$respuesta' WHERE id = $respuestaID";
            $resultado = mysqli_query($conexion, $query);

            if (!$resultado) {
                echo "Error al actualizar la respuesta $respuestaID.";
            } else{
                var_dump($respuestaID);
                echo $respuestaID;
                echo "hols";
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Examen</title>
  <link rel="stylesheet" href="assets/css/actualizar-exam.css">
</head>
<body>
    <header>
    <p>Editar Examen</p>
    </header>
   
    <form method="POST" action="">
    <label for="nombre">Nombre del Examen:</label>
    <input type="text" name="nombre" id="nombre" value="<?php echo $examen['nombre']; ?>">
    
    <!-- Mostrar todas las preguntas y sus respuestas -->
    <?php foreach($preguntas_respuestas as $indice => $pregunta_respuestas) { ?>
        <label for="pregunta_<?php echo $indice; ?>">Pregunta:</label>
        <input type="text" name="pregunta[<?php echo $indice; ?>]" id="pregunta_<?php echo $indice; ?>" value="<?php echo $pregunta_respuestas['pregunta']['pregunta']; ?>">
        <label for="respuestas">Respuestas:</label>
        <?php foreach($pregunta_respuestas['respuestas'] as $respuestaIndice => $respuesta) { ?>
            <input type="text" name="respuesta[<?php echo $indice; ?>][<?php echo $respuestaIndice; ?>]" id="respuesta_<?php echo $indice; ?>_<?php echo $respuestaIndice; ?>" value="<?php echo $respuesta; ?>">
        <?php } ?>
    <?php } ?>
    <br>
    <input type="submit" value="Guardar Cambios">
</form>

</body>
</html>
