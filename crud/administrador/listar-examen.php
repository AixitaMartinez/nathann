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
include   '../php/conexion_be.php';
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
    <title>Document</title>
    <style>
        body {
            font-family: Inter, sans-serif;
            font-size: 1.6rem;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        h2 {
            margin-left: 214px;
            color: #333;
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
            background-color: #00272b;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
        /* Cabecera header*/
        .cabecera{
            background-color: #00272b;
            width:100%;
            padding-top: 30px;
            padding-bottom: 35px;
        }
        .btn-container {
            text-align: center;
            display: flex;
            justify-content: center;
        }
        .btn {
            display: inline-block;
            width: 150px;
            margin: 0 10px; /* Ajusta el margen entre botones */
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #e0ff4f;
        }

        .no-registros {
            text-align: center;
            font-weight: 700;
            padding: 2rem 0;
        }

        /* Estilos para los iconos */
    .iconos {
        display: flex;
        align-items: center; /* Centra verticalmente los elementos */
    }

    .iconos i {
        background-color: #3498db;
        color: #fff;
        border-radius: 50%;
        padding: 7px;
        transition: background-color 0.3s, transform 0.3s;
        margin-left: 10px;
    }

    .iconos i:hover {
        background-color: #e0ff4f;
        transform: scale(1.1); /* Aumenta el tamaño al pasar el cursor */
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
            <a href="crear-examen.php" class="btn">
                Crear examen
            </a>
            <a href="crear-examen.php" class="btn">
                Calificaciones
            </a>
        </div>
    </div>
</header>
<h2> Mis exámenes</h2>
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
                        <a href="editar-examen.php?id=<?php echo $examen['id']; ?>">
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