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
// Obtener el ID del docente
$id_docente = $_SESSION['docentes'];
$query = "SELECT nombre FROM docentes WHERE id_cedula = '$id_docente'";
$result = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($result);

/*
if (!$result) {
    echo 'Error en la consulta: ' . mysqli_error($conexion); // Muestra el error SQL
    exit;
}
*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../material/assets/css/styles.css">
    <title>Inicio</title>
    <script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>
    <style>
        /* styles.css */
    .card {
        width: 340px; /* Ancho de 180px */
        height: 180px; /* Altura igual al ancho para hacerlo cuadrado */
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
       
        text-align: center;
        transition: transform 0.3s ease-in-out;
    }

.card:hover {
    transform: scale(1.05);
}

.btn {
    background-color: #007bff;
    color: #fff;
    padding: 20px 80px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease-in-out;
}

.btn:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body id="body">
    
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

            <a href="Bienvenida_docente.php" class="selected">
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
            
            <a href="#">
                <div class="option">
                    <i class="fa-solid fa-bars-progress"></i>
                    <h4>Simulacros</h4>
                </div>
            </a>
            <a href="ver_estudiantes.php">
                <div class="option">
                <i class="fa-solid fa-user-group"></i>
                    <h4>Ver estudiantes</h4>
                </div>
            </a>
            <a href="actualizar_docentes.php">
                <div class="option">
                    <i class="fa-solid fa-user"></i>
                    <h4>Perfil</h4>
                </div>
            </a>

            <a href="../php/cerrar_sesion.php">
                <div class="option">
                <i class="fa-solid fa-arrow-up-from-bracket fa-rotate-90"></i>
                    <h4>cerrar sesión</h4>
                </div>
            </a>
        </div>

    </div>

    <main>
        <h1>Bienvenido docente <?php echo $row['nombre']?></h1><br>
        <p>¿Qué deseas hacer hoy?</p>
        <div class="card">
            <a href="listar-examen.php" class="btn">Editar exámenes</a>
        </div>
    </main>

    <script src="../material/assets/js/script.js"></script>
</body>
</html>
