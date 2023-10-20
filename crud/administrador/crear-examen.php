<?php
session_start();
include '../php/conexion_be.php';
if (!isset($_SESSION['docentes'])) {
    echo '
    <script>
    alert("Por favor, debes iniciar sesión");
    window.location = "../index.php";
    </script>';
    session_destroy();
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo_examen = $_POST['titulo_examen'];
    $num_preguntas = intval($_POST['num_preguntas']); // Obtener el número de preguntas

    // Insertar nombre del examen
    $query = "INSERT INTO examenes (nombre) VALUES ('$titulo_examen')";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        // Obtener el ID del examen
        $ultimoexamen = mysqli_insert_id($conexion);

        // Iterar a través de las preguntas y opciones
        for ($i = 1; $i <= $num_preguntas; $i++) {
            $pregunta = $_POST["preg$i"];
            $correcta = $_POST["preg{$i}_correcta"];
        
            // Insertar pregunta en tabla examen_preguntas
            $query = "INSERT INTO examen_preguntas (pregunta, correcta, examen_id) VALUES ('$pregunta', '$correcta', '$ultimoexamen')";
            $resultado = mysqli_query($conexion, $query);

        
            if ($resultado) {
                $ultimapregunta = mysqli_insert_id($conexion);
                // Iterar a través de las opciones A, B, C y D
                for ($letra = 'a'; $letra <= 'd'; $letra++) {
                    $opcion = $_POST["preg{$i}_opcion_{$letra}"];
                    
                    echo "Opción $letra: $opcion<br>";
                    // Verificar si $opcion no es nulo ni una cadena vacía
                    if (!is_null($opcion) && $opcion !== "") {

                        // Insertar opciones en tabla respuestas
                        $query = "INSERT INTO respuestas (inciso, respuesta, pregunta_id, examen_id) VALUES ('$letra', '$opcion', '$ultimapregunta', '$ultimoexamen')";
                        $resultado = mysqli_query($conexion, $query);
                        
                        if (!$resultado) {
                            // Manejar el caso en el que la inserción falla
                            echo '<script>
                                alert("Error al guardar una respuesta");
                            </script>';
                        }
                    }
                }

            }
        }
        // Asignar a todos los alumnos
        $query = "SELECT * FROM estudiantes";
        $estudiantes = mysqli_query($conexion, $query);
        while ($estudiante = mysqli_fetch_assoc($estudiantes)) {
            $estudiante_id = $estudiante['id'];
            $query = "INSERT INTO usuarios_examenes (estudiante_id, docente_id, examen_id, aciertos) VALUES ($estudiante_id, {$_SESSION['docentes']}, $ultimoexamen, 0)";
            $resultado = mysqli_query($conexion, $query);
        }
        $resultado = mysqli_query($conexion, $query);
    } else {
        echo '<script>
            alert("Error al guardar el examen");
            </script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Examen</title>
    <link rel="stylesheet" href="assets/css/styles_crearexam.css">
</head>
<body>
    <center><header> <p>Crea un examen</p></header></center>
    <div class="container">
    <form action="crear-examen.php" class="form_examen" method="post">
        <p class="intrucciones-examen">Asigna un nombre al examen.</p>
        <hr class="separador">
      

        <div class="form_grupo">
            <label for="titulo_examen">Nombre</label>
            <input class="form_title" type="text" name="titulo_examen" id="titulo_examen">
        </div>

        <hr class="separador">
        <p class="intrucciones-examen">Redacta las preguntas y sus respuestas.</p>
        <input type="hidden" name="num_preguntas" id="num_preguntas" value="1" >
        <div id="preguntas-container">
            <!-- Contenedor de preguntas -->
            <div class="form_grupo form_grupo-pregunta">
                <div class="fila">
                    <label for="preg1" class="numero-pregunta">1</label>
                    <input class="pregunta" type="text" name="preg1" id="preg1">

                    <!-- Seleccionar opción correcta -->
                    <label for="preg1_correcta" class="opcion-correcta">Opción correcta</label>
                    <select name="preg1_correcta" id="preg1_correcta"class="input-opciones">
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>

                <div class="fila">
                    <!-- Opciones -->
                    <div class="input-opciones">
                        <div class="opcion">
                            <label>A</label>
                            <input type="text" name="preg1_opcion_a">
                        </div>

                        <div class="opcion">
                            <label>B</label>
                            <input type="text" name="preg1_opcion_b">
                        </div>

                        <div class="opcion">
                            <label>C</label>
                            <input type="text" name="preg1_opcion_c">
                        </div>
                        <div class="opcion">
                            <label>D</label>
                            <input type="text" name="preg1_opcion_d">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Pregunta examen -->
        </div>
        <button type="button" id="agregar-pregunta">Agregar otra pregunta</button>
        <input class="btn btn-principal btn-examen" type="submit" value="Guardar">
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const preguntasContainer = document.getElementById("preguntas-container");
            const agregarPreguntaBtn = document.getElementById("agregar-pregunta");

            let numeroPregunta = 1;

            agregarPreguntaBtn.addEventListener("click", function () {
                numeroPregunta++;
                const nuevaPregunta = document.createElement("div");
                nuevaPregunta.className = "form_grupo form_grupo-pregunta";

                nuevaPregunta.innerHTML = `
                <div class="fila">
                        <label for="preg${numeroPregunta}" class="numero-pregunta">${numeroPregunta}</label>
                        <input class="pregunta" type="text" name="preg${numeroPregunta}" id="preg${numeroPregunta}">

                        <!-- Seleccionar opción correcta -->
                        <label for="preg${numeroPregunta}_correcta" class="opcion-correcta">Opción correcta</label>
                        <select name="preg${numeroPregunta}_correcta" id="preg${numeroPregunta}_correcta">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <div class="fila">
                        <!-- Opciones -->
                        <div class="input-opciones">
                            <div class="opcion">
                                <label>A</label>
                                <input type="text" name="preg${numeroPregunta}_opcion_a">
                            </div>

                            <div class="opcion">
                                <label>B</label>
                                <input type="text" name="preg${numeroPregunta}_opcion_b">
                            </div>

                            <div class="opcion">
                                <label>C</label>
                                <input type="text" name="preg${numeroPregunta}_opcion_c">
                            </div>
                            <div class="opcion">
                                <label>D</label>
                                <input type="text" name="preg${numeroPregunta}_opcion_d">
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById("num_preguntas").value = numeroPregunta;
                preguntasContainer.appendChild(nuevaPregunta);
            });
        });
    </script>
    </form>
    </div>
</body>
</html>