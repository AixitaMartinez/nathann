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
    
    
    // Obtiene el ID del usuario(o sea, el estudiante) actual
    $user_id = intval($_SESSION['estudiantes']);
    
    //EN CASO DE QUE NO SE ENCUENTRE EL ID DEL Examen
    if (!$id){
        header ('location: ../ver_examen');
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
    //Respuesta que selecciona el estudiante
    if ($_SERVER['REQUEST_METHOD']=== 'POST'){
        
        $aciertos = 0;
        foreach ($preguntas_respuestas as $indice => $pregunta_respuestas){
            $respuesta_correcta = $_POST['preg_correcta'][$indice];
    
    
            
            $query = "SELECT correcta FROM examen_preguntas WHERE examen_id = $id AND id = $preguntaID";
    
            $resultado = mysqli_fetch_assoc(mysqli_query($conexion, $query));
            if ($respuesta_correcta === $resultado['correcta']){
                $aciertos++;
            }
    
        }
        echo "Aciertos" . $aciertos;
        $query = "UPDATE usuarios_examenes SET aciertos = {$aciertos} WHERE examen_id = {$id} AND estudiante_id = {$user_id}";
        $resultado = mysqli_query($conexion, $query);
        // Después de calcular los aciertos
    
    }

    // Verificar si se ha enviado el formulario (cuando el estudiante ha respondido)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $respuesta_estudiante = $_POST['preg_correcta'][$indice];
        $respuesta_correcta = $pregunta_respuestas['pregunta']['correcta'];

        echo '<p>Respuesta del estudiante: ' . $respuesta_estudiante . '</p>';
        echo '<p>Respuesta correcta: ' . $respuesta_correcta . '</p>';

        // Comprobar si la respuesta del estudiante coincide con la respuesta correcta
        if ($respuesta_estudiante === $respuesta_correcta) {
            echo '<p>Resultado: Correcta</p>';
        } else {
            echo '<p>Resultado: Incorrecta</p>';
        }
    }
     
?>    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuestas del Examen</title>
</head>
<body>

    <h1>Respuestas del Examen: <?php echo $examen['nombre']; ?></h1>
    <?php foreach ($preguntas_respuestas as $indice => $pregunta_respuestas) { ?>
        <label for="pregunta_<?php echo $indice; ?>">Pregunta:</label>
        <input type="text" name="pregunta[<?php echo $indice; ?>]" id="pregunta_<?php echo $indice; ?>" value="<?php echo $pregunta_respuestas['pregunta']['pregunta']; ?>" disabled><br>
        <label for="preg_correcta_<?php echo $indice; ?>" class="opcion-correcta">Opción correcta</label><br>
        <select name="preg_correcta[<?php echo $indice; ?>]" id="preg_correcta_<?php echo $indice; ?>">
            <option value="a">A</option>
            <option value="b">B</option>
            <option value="c">C</option>
            <option value="d">D</option>
        </select><br>
        <label for="respuestas">Respuestas:</label>
        <?php foreach ($pregunta_respuestas['respuestas'] as $respuestaIndice => $respuesta) { ?>
            <input type="text" name="respuesta[<?php echo $indice; ?>][<?php echo $respuestaIndice; ?>]" id="respuesta_<?php echo $indice; ?>_<?php echo $respuestaIndice; ?>" value="<?php echo $respuesta; ?>" disabled><br>
        <?php } 
        }?>

</body>
</html>
