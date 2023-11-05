<?php
session_start();
if (!isset($_SESSION['estudiantes'])) {
    echo '
    <script>
    alert("Por favor, debes iniciar sesión");
    window.location = "../index.php";
    </script>';

    session_destroy();
    exit;
}

include '../php/conexion_be.php';

// Obtener el ID del estudiante a actualizar (POST)
$id_estudiante = $_SESSION['estudiantes'];

// Consulta para obtener la lista actualizada de exámenes
$query = "SELECT * FROM examenes";
$resultado = mysqli_query($conexion, $query);

// Consulta para obtener los aciertos de cada examen del estudiante
$query_aciertos = "SELECT examen_id, aciertos FROM usuarios_examenes WHERE estudiante_id = '$id_estudiante'";
$resultado_aciertos = mysqli_query($conexion, $query_aciertos);

// Crear un array asociativo para almacenar los aciertos por examen
$aciertos_por_examen = array();
while ($row = mysqli_fetch_assoc($resultado_aciertos)) {
    $aciertos_por_examen[$row['examen_id']] = $row['aciertos'];
}

$query_preguntas = "SELECT pregunta FROM examen_preguntas";
$resultado_preguntas = mysqli_query($conexion, $query_preguntas);
$num_preguntas = mysqli_num_rows($resultado_preguntas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   <link rel="stylesheet" href="assets/css/ver_examen.css">
   
<script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>
</head>
<body id=body>
<header>
        <div class="icon__menu">
            <i class="fas fa-bars" id="btn_open"></i>
        </div>
    </header>

    <div class="menu__side" id="menu_side">

        <div class="name__page">
            <i class="fa-solid fa-earth-americas"></i>
            <h4>Ciencias sociales</h4>
        </div>

        <div class="options__menu">	

            <a href="bienvenida.php">
                <div class="option">
                    <i class="fas fa-home" title="Inicio"></i>
                    <h4>Inicio</h4>
                </div>
            </a>

            <a href="#">
                <div class="option">
                    <i class="far fa-file" title="Portafolio"></i>
                    <h4>Material</h4>
                </div>
            </a>
            
            <a href="ver_examen.php" class="selected">
                <div class="option">
                    <i class="fa-solid fa-bars-progress"></i>
                    <h4>Simulacros</h4>
                </div>
            </a>

            <a href="actualizar_estudiante.php">
                <div class="option">
                    <i class="fa-solid fa-user"></i>
                    <h4>Perfil</h4>
                </div>
            </a>

            <a href="../php/cerrar_sesion.php">
                <div class="option">
                    <i class="fa-regular fa-circle-xmark"></i>
                    <h4>cerrar sesión</h4>
                </div>
            </a>
        </div>

    </div>


<div class="container">
    <table>
        <thead>
            <th>Nombre</th>
            <th>Aciertos</th>
            <th>Acciones</th>
        </thead>
        <tbody>
        <?php
        if ($resultado->num_rows >= 1) {
            while ($examen = mysqli_fetch_assoc($resultado)) {
                $examen_id = $examen['id'];

                // Verificar si el estudiante ya ha realizado el examen
                $query_realizado = "SELECT examen_id FROM usuarios_examenes WHERE estudiante_id = '$id_estudiante' AND examen_id = '$examen_id'";
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
                    echo "<td class='iconos'>";
                    echo "<a href='examen/realizar-examen.php?id={$examen['id']}'>";
                    echo "<i class='fa-solid fa-eye'></i> Realizar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr>";
            echo "<td colspan='3' class='no-registros'>No hay registros</td>";
            echo "</tr>";
        }
        ?>

            
        </tbody>
    </table>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>
