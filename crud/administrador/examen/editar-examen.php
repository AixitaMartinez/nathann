<?php
include   '../../php/conexion_be.php';
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
// Eliminar examen
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];

    $query = "DELETE FROM usuarios_examenes WHERE examen_id = {$id}";
    $resultado = mysqli_query($conexion, $query);

    $query = "DELETE FROM respuestas WHERE examen_id = {$id}";
    $resultado = mysqli_query($conexion, $query);

    $query = "DELETE FROM examen_preguntas WHERE examen_id = {$id}";
    $resultado = mysqli_query($conexion, $query);

    $query = "DELETE FROM examenes WHERE id = {$id}";
    $resultado = mysqli_query($conexion, $query);
}

// Consulta para obtener la lista actualizada de exámenes
$query = "SELECT * FROM examenes";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver exámenest</title>
    <style>
        body {
    font-family: Inter, sans-serif;
    font-size: 1.6rem;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

header {
    background-color: #CACFD2;
    color: #fff;
    padding: 25px;
    text-align: center;
}

nav ul {
    list-style: none;
    padding: 0;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

.container {
    max-width: 1440px;
    width: 70%;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    margin-top: 20px;
    margin-bottom: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 1.5rem;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #7DCEA0;
    color: #fff;
}

tr:hover {
    background-color:#CACFD2;
}

.btn-container {
    text-align: center;
    display: flex;
    justify-content: center;
}

.btn {
    display: inline-block;
    width: 150px;
    margin: 0 10px;
    padding: 10px 20px;
    background-color:#7DCEA0;
    color: #fff;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: black;
}

.no-registros {
    text-align: center;
    font-weight: 700;
    padding: 2rem 0;
}

.iconos {
    display: flex;
    align-items: center;
}

.iconos i {
    background-color: #7DCEA0;
    color: #fff;
    border-radius: 50%;
    padding: 7px;
    transition: background-color 0.3s, transform 0.3s;
    margin-left: 10px;
}

.iconos i:hover {
    background-color: black ;
    transform: scale(1.1);
    cursor: pointer;
}

    </style>
   <script>
    function confirmarEliminacion(id) {
        var confirmacion = confirm("¿Estás seguro de que deseas eliminar este examen?");
        if (confirmacion) {
            document.getElementById("eliminarRegistro_" + id).submit();
        }
    }
</script>
    <script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="cabecera">
        <div class="btn-container">
            <a href="../bienvenida_docente.php" class="btn">
            <i class="fa-solid fa-house"></i> Regresar
            </a>
            <a href="crear-examen.php" class="btn">
                Crear examen
            </a>
            <a href="calificaciones.php" class="btn">
                Calificaciones
            </a>
        </div>
    </div>
</header>

<div class="container">
    <table>
        <thead>
            <th>Nombre</th>
            <th>Acciones</th>
        </thead>
        <tbody>
            <?php  if ($resultado->num_rows >= 1):?>
            <?php while($examen = mysqli_fetch_assoc($resultado)):?>
                <tr>
                    <td><?php echo $examen['nombre']; ?></td>
                    <td class="iconos">
                        <a href="actualizar-examen.php?id=<?php echo $examen['id']; ?>">
                            <i class="icono icono-editar fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="" method="POST" id="eliminarRegistro_<?php echo $examen['id']; ?>">
                            <input type="hidden" name="id" value="<?php echo $examen['id']; ?>">
                            <a href="#" onclick="confirmarEliminacion(<?php echo $examen['id']; ?>)">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="no-registros">No hay registros</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>