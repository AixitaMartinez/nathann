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
var_dump($id);
$query = "SELECT * FROM examenes WHERE id = $id";
$resultado = mysqli_query($conexion, $query);
$examen = mysqli_fetch_assoc($resultado);

// Verificar si el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre']; // Nombre del examen
 
    $query = "UPDATE examenes SET nombre = '$nombre' WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo "Examen actualizado correctamente.";
   
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
        <!-- no sé que hice con lo demás xd-->
        
 

        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>