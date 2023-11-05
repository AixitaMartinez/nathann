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

// Realizar una consulta SQL para obtener los datos de los estudiantes
$queryy = "SELECT * FROM estudiantes";
$result = mysqli_query($conexion, $queryy);

// Consulta para obtener la lista actualizada de exámenes
$query = "SELECT * FROM examenes";
$resultado = mysqli_query($conexion, $query);

// Crear un array para almacenar el número de preguntas de cada examen
$num_preguntas_por_examen = array();
while ($examen = mysqli_fetch_assoc($resultado)) {
    $examen_id = $examen['id'];
    $query_num_preguntas = "SELECT COUNT(*) as num_preguntas FROM examen_preguntas WHERE examen_id = $examen_id";
    $resultado_num_preguntas = mysqli_query($conexion, $query_num_preguntas);
    $row_num_preguntas = mysqli_fetch_assoc($resultado_num_preguntas);
    $num_preguntas_por_examen[$examen_id] = $row_num_preguntas['num_preguntas'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inicio</title>
    <script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>   
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
        }

        .contenedor {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        table th {
            background-color:#1c6a28;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Estilos para el botón REGRESAR */
        .boton-regresar {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #1c6a28;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .boton-regresar:hover {
            background-color: #555;
        }

        /* Estilos para los enlaces */
        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Lista de Estudiantes</h2>
        <p>Nota: Al dar clic sobre el estudiante, se muestran sus calificaciones individuales.</p>
        <table>
            <tr>
                <th>Ti/CC</th>
                <th>Nombre</th>
                <th>Grado</th>
                <th>Examen</th>
                <th>Aciertos</th>
                <th>Núm de Preguntas</th>
                <th>Calificacion</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><a href='calificacion-individual.php?id=<?php echo $row['id']; ?>'> <p><?php echo $row['nombre']; ?></p></a></td>
                    <?php 
                    // Obtener la descripción del grado para cada estudiante
                    $id_grado = $row['id_grado'];
                    $queryg = "SELECT descripción FROM grados WHERE id = '$id_grado'";
                    $resultg = mysqli_query($conexion, $queryg);
                    $rowg = mysqli_fetch_assoc($resultg);
                    ?>
                    <td><?php echo $rowg['descripción']; ?></td>

                    <?php
                    // Obtener las calificaciones de los exámenes de cada estudiante
                    $id_estudiante = $row['id'];
                    $query_calificaciones = "SELECT examen_id, aciertos FROM usuarios_examenes WHERE estudiante_id = '$id_estudiante'";
                    $resultado_calificaciones = mysqli_query($conexion, $query_calificaciones);

                    // Iterar a través de las calificaciones de los exámenes de cada estudiante
                    while ($calificacion = mysqli_fetch_assoc($resultado_calificaciones)) {
                        // Obtener información del examen
                        $examen_id = $calificacion['examen_id'];
                        $query_examen_info = "SELECT nombre FROM examenes WHERE id = '$examen_id'";
                        $resultado_examen_info = mysqli_query($conexion, $query_examen_info);
                        $examen_info = mysqli_fetch_assoc($resultado_examen_info);
                        
                        // Mostrar cada evaluación en una fila separada
                        echo "<tr>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td>{$examen_info['nombre']}</td>";
                        echo "<td>{$calificacion['aciertos']}/{$num_preguntas_por_examen[$examen_id]}</td>";
                        echo "<td>{$num_preguntas_por_examen[$examen_id]}</td>";
                        $calificacion_total = round(($calificacion['aciertos'] * 100)/ $num_preguntas_por_examen[$examen_id]);
                        echo "<td>$calificacion_total</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tr>
        </table>
            
        <a href="editar-examen.php" class="boton-regresar">REGRESAR</a>
        <a href="fpdf/PruebaV.php" class="boton-generar-pdf"><i class="fa-solid fa-file-pdf"></i>Generar PDF</a>

    </div>
</body>
</html>
