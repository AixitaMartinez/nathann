<?php
include '../../php/conexion_be.php';
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
// Consultar id del examen
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

// REDIRECCIONAR SI NO SE ENCUENTRA EL EXAMEN
if (!$id) {
    header('location: editar-examen.php');
    exit;
}
$examenGuardadoExitosamente = false; // Variable para controlar el mensaje

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

    // Crear un array para almacenar todas las respuestas
    $respuestas_pregunta = [];

    while ($respuestas = mysqli_fetch_assoc($resultado_respuestas)) {
        $respuestas_pregunta[] = $respuestas; // Almacena toda la información de la respuesta
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

    if (!$resultado) {
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

        // Obtiene la nueva opción correcta del formulario
        $nuevaOpcionCorrecta = $_POST['respuesta_correcta'][$indice];

        // Actualiza la opción correcta en la base de datos
        $query = "UPDATE examen_preguntas SET correcta = '$nuevaOpcionCorrecta' WHERE id = $preguntaID";
        $resultado = mysqli_query($conexion, $query);

        if (!$resultado) {
            echo "Error al actualizar la opción correcta para la pregunta $preguntaID.";
        }
    }

    // Luego, actualiza las respuestas por separado (fuera del bucle de preguntas)
    foreach ($_POST['respuesta'] as $indice => $respuestas) {
        foreach ($respuestas as $respuestaIndice => $respuesta) {
            $respuestaID = $preguntas_respuestas[$indice]['respuestas'][$respuestaIndice]['id'];
            // Actualiza la respuesta en la base de datos
            $query = "UPDATE respuestas SET respuesta = '$respuesta' WHERE id = $respuestaID";
            $resultado = mysqli_query($conexion, $query);

            if (!$resultado) {
                echo "Error al actualizar la respuesta $respuestaID.";
            }
        }
    }
    $examenGuardadoExitosamente = true; 
}

if ($examenGuardadoExitosamente) {
    echo '<script>
    alert("Examen guardado satisfactoriamente");
    window.location = "actualizar-examen.php";
    </script>';}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Examen</title>
  <link rel="stylesheet" href="../assets/css/actualizar-exam.css">
</head>
<body>
    <header>
    <p>Editar Examen</p>
    </header>
  <div class="container">
    <form method="POST" action="" class="form_exam">
        <div class="form_grupo">
            <label for="nombre">Nombre del Examen:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $examen['nombre']; ?>">
        </div>
        <hr class="separador">
        
        <!-- Mostrar todas las preguntas y sus respuestas en contenedores separados -->
        <?php foreach($preguntas_respuestas as $indice => $pregunta_respuestas) { ?>
            <div class="pregunta-container">
                <div class="pregunta-box">
                    <label for="pregunta_<?php echo $indice; ?>">Pregunta:</label>
                    <textarea name="pregunta[<?php echo $indice; ?>]" id="pregunta_<?php echo $indice; ?>"><?php echo $pregunta_respuestas['pregunta']['pregunta']; ?></textarea>
                </div>
                <div class="respuestas-container">
                    <?php $opciones = ['a', 'b', 'c', 'd']; ?>
                    <?php foreach($pregunta_respuestas['respuestas'] as $respuestaIndice => $respuesta) { ?>
                        <div class="respuesta-box">
                            <label for="respuesta_<?php echo $indice; ?>_<?php echo $respuestaIndice; ?>"><?php echo $opciones[$respuestaIndice]; ?>:</label>
                            <input type="text" name="respuesta[<?php echo $indice; ?>][<?php echo $respuestaIndice; ?>]" id="respuesta_<?php echo $indice; ?>_<?php echo $respuestaIndice; ?>" value="<?php echo $respuesta['respuesta']; ?>">
                            <label for="respuesta_correcta_<?php echo $indice; ?>">Correcta</label>
                            <input type="radio" name="respuesta_correcta[<?php echo $indice; ?>]" value="<?php echo $opciones[$respuestaIndice]; ?>" <?php if ($pregunta_respuestas['pregunta']['correcta'] == $opciones[$respuestaIndice]) echo 'checked'; ?>>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <br>
        <input type="submit" value="Guardar Cambios">
    </form>
</div>
    
</body>
</html>
