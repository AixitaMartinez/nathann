<?php
session_start();
if (!isset($_SESSION['estudiantes'])) {
    echo '
    <script>
    alert ("Por favor, debes inicar sesión");
    window.location = "../index.php";
    </script>';
    session_destroy();
    exit;
}

include '../../php/conexion_be.php';
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

// Obtiene el ID del usuario (es decir, el estudiante) actual
$user_id = intval($_SESSION['estudiantes']);

// En caso de que no se encuentre el ID del Examen
if (!$id) {
    header('location: ../ver_examen');
    echo '<script>No se encontró el examen"</script>';
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

// Mezclar aleatoriamente las preguntas
shuffle($preguntas_respuestas);

// Obtener el índice de la pregunta actual desde la URL
$indice_pregunta_actual = isset($_GET['indice']) ? (int)$_GET['indice'] : 0;

// Respuesta que selecciona el estudiante
// Antes del bucle foreach
$respuestas_estudiante = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // código de comprobación de respuestas y actualización de aciertos
    $aciertos = 0;
    foreach ($preguntas_respuestas as $indice => $pregunta_respuestas){
        $respuesta_correcta = $_POST['preg_correcta'][$indice_pregunta_actual];

        $query = "SELECT correcta FROM examen_preguntas WHERE examen_id = $id AND id = $preguntaID";
        $resultado = mysqli_fetch_assoc(mysqli_query($conexion, $query));
        if ($respuesta_correcta === $resultado['correcta']){
            $aciertos++;
        }
        $respuestas_estudiante[$indice] = $respuesta_correcta;
    }
    $query = "UPDATE usuarios_examenes SET aciertos = {$aciertos} WHERE examen_id = {$id} AND estudiante_id = {$user_id}";
    $resultado = mysqli_query($conexion, $query);

    // Redirigir a la siguiente pregunta o mostrar un mensaje de finalización
    $indice_pregunta_actual++;
    if ($indice_pregunta_actual < count($preguntas_respuestas)) {
        header("Location: realizar-examen.php?id=$id&indice=$indice_pregunta_actual");
        exit;
    } else {
        echo '
        <script>
        alert ("Examen realizado con éxito");
        </script>';

        // Aquí puedes realizar acciones adicionales después de completar el examen.
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/examen.css">
    <title>Quiz</title>
    <script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="quiz-container">
<div class="contador-preguntas">
    Pregunta <?php echo $indice_pregunta_actual + 1; ?> de <?php echo count($preguntas_respuestas); ?>
</div>
        <form class="quiz-form" method="post">
            <h1 class="quiz-title">Examen: <?php echo $examen['nombre']; ?></h1>
            
            <?php if ($indice_pregunta_actual < count($preguntas_respuestas)) { ?>
                <p class="quiz-instructions">Selecciona la opción que creas correcta.</p>
                <div class="quiz-question">
               <label for="pregunta_<?php echo $indice_pregunta_actual; ?>"><b><?php echo $indice_pregunta_actual+1 ?>. </b></label>
                    <input type="text" name="pregunta[<?php echo $indice_pregunta_actual; ?>]" id="pregunta_<?php echo $indice_pregunta_actual; ?>" value="<?php echo $preguntas_respuestas[$indice_pregunta_actual]['pregunta']['pregunta']; ?>" disabled><br>
                </div>

                <label for="preg_correcta_<?php echo $indice_pregunta_actual; ?>" class="quiz-option-label">Escoge una letra</label><br>
                <select name="preg_correcta[<?php echo $indice_pregunta_actual; ?>]" id="preg_correcta_<?php echo $indice_pregunta_actual; ?>" class="quiz-select">
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select>

                <?php
                $letras = ['a', 'b', 'c', 'd'];

                foreach ($preguntas_respuestas[$indice_pregunta_actual]['respuestas'] as $respuestaIndice => $respuesta) {
                    echo '<div class="opcion">';
                    echo '<label for="respuesta_' . $indice_pregunta_actual . '_' . $respuestaIndice . '">' . $letras[$respuestaIndice] . '. </label>';
                    echo '<input type="text" name="respuesta[' . $indice_pregunta_actual . '][' . $respuestaIndice . ']" id="respuesta_' . $indice_pregunta_actual . '_' . $respuestaIndice . '" value="' . $respuesta . '" disabled>';
                    echo '</div>';
                }
                echo '<button class="quiz-button" type="submit">Siguiente</button>';
  } ?>
              
        </form>
        

<?php
if ($indice_pregunta_actual >= count($preguntas_respuestas)) {
    echo '<p>¡Has completado el examen!</p>';
    $porcentaje_aciertos = ($aciertos / count($preguntas_respuestas)) * 100;
    echo '<p>Porcentaje de aciertos: ' . $porcentaje_aciertos . '%</p>';

    // Ahora, muestra todas las respuestas del estudiante y las respuestas correctas
    foreach ($preguntas_respuestas as $indice => $pregunta_respuestas) {
        $respuesta_correcta = $respuestas_estudiante[$indice];
        $query = "SELECT correcta FROM examen_preguntas WHERE examen_id = $id AND id = {$pregunta_respuestas['pregunta']['id']}";
        $resultado = mysqli_fetch_assoc(mysqli_query($conexion, $query));
        $respuesta_correcta_db = $resultado['correcta'];

        echo '<p>Respuesta del estudiante para la pregunta ' . ($indice + 1) . ': ' . $respuesta_correcta . '</p>';
        echo '<p>Respuesta correcta para la pregunta ' . ($indice + 1) . ': ' . $respuesta_correcta_db . '</p>';

        // Comprobar si la respuesta del estudiante coincide con la respuesta correcta
        if ($respuesta_correcta === $respuesta_correcta_db) {
            echo '<p>Resultado para la pregunta ' . ($indice + 1) . ': Correcta</p>';
        } else {
            echo '<p>Resultado para la pregunta ' . ($indice + 1) . ': Incorrecta</p>';
        }
    }
}
?>

    </div>
</body>
</html>

