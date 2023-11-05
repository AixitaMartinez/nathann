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

include '../../../php/conexion_be.php';

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
require('fpdf.php'); // Asegúrate de que esta ruta sea correcta



class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(0, 10, 'Reporte de Calificaciones de Estudiantes', 0, 1, 'C');
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$pdf->SetFillColor(192, 192, 192); // Color gris (valores RGB)
$pdf->Cell(25, 10, 'Ti/CC', 1, 0, 'C', true); // true indica que el fondo debe ser coloreado
$pdf->Cell(70, 10, 'Nombre', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Grado', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Examen', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Aciertos', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Núm de Preguntas', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Calificacion', 1, 1, 'C', true); // 1 indica que se mueve a la siguiente fila

$pdf->SetFillColor(255, 255, 255); // Restablece el fondo a blanco para el contenido

// Definir una nueva consulta SQL para obtener los datos de los estudiantes y sus calificaciones
$queryy = "SELECT * FROM estudiantes";
$result = mysqli_query($conexion, $queryy);

// Loop para agregar los datos de los estudiantes
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Ln();
    $pdf->Cell(25, 10, $row['id'], 1);
    $pdf->Cell(70, 10, utf8_decode($row['nombre']), 1); // Aplicar utf8_decode al nombre
    // Obtener la descripción del grado para cada estudiante
    $id_grado = $row['id_grado'];
    $queryg = "SELECT descripción FROM grados WHERE id = '$id_grado'";
    $resultg = mysqli_query($conexion, $queryg);
    $rowg = mysqli_fetch_assoc($resultg);
    $pdf->Cell(30, 10, utf8_decode($rowg['descripción']), 1); // Aplicar utf8_decode a la descripción del grado

    $id_estudiante = $row['id'];
    $query_calificaciones = "SELECT examen_id, aciertos FROM usuarios_examenes WHERE estudiante_id = '$id_estudiante'";
    $resultado_calificaciones = mysqli_query($conexion, $query_calificaciones);
    
    while ($calificacion = mysqli_fetch_assoc($resultado_calificaciones)) {
        // Obtener información del examen
        $examen_id = $calificacion['examen_id'];
        $query_examen_info = "SELECT nombre FROM examenes WHERE id = '$examen_id'";
        $resultado_examen_info = mysqli_query($conexion, $query_examen_info);
        $examen_info = mysqli_fetch_assoc($resultado_examen_info);

        $pdf->Cell(40, 10, utf8_decode($examen_info['nombre']), 1); // Aplicar utf8_decode al nombre del examen
        $pdf->Cell(30, 10, $calificacion['aciertos'], 1);
        $pdf->Cell(30, 10, $num_preguntas_por_examen[$examen_id], 1);
        $calificacion_total = round($calificacion['aciertos'] * 100 / $num_preguntas_por_examen[$examen_id]);
        $pdf->Cell(30, 10, $calificacion_total . '%', 1);
    }
}

$pdf->Output();
?>
