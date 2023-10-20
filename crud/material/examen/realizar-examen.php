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

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        /* Estilos generales */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
}

/* Encabezado */
h1 {
    text-align: center;
}

/* Estilos para las preguntas */
form.form_examen {
    margin-top: 20px;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
}

p.instrucciones-examen {
    font-weight: bold;
    font-size: 18px;
}

/* Estilos para las etiquetas de las preguntas */
label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

/* Estilos para las opciones correctas */
label.opcion-correcta {
    color: green;
}

/* Estilos para los select de opciones correctas */
select {
    width: 100px;
}

/* Estilos para las respuestas */
input[type="text"] {
    width: 40%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Estilos para el botón de enviar */
input[type="submit"] {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

/* Cambiar el color del botón al pasar el mouse */
input[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    

 <form class="form_examen" method="post">
    <p class="intrucciones-examen">Selecciona la opción que creas correcta.</p>
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
        <?php } ?>
    <?php } ?>
    <input type="submit" value="Enviar Examen">
</form>

</body>
</html>