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
include '../php/conexion_be.php';

// Obtener el ID del estudiante a actualizar ( POST)
$id_estudiante = $_SESSION['estudiantes'];
$query = "SELECT nombre FROM estudiantes WHERE id = '$id_estudiante'";
$result = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($result);

//obtener categorias y contenido
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Inicio</title>
    <script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>
    <style>
        .container {
        max-width: 60%;
        width: 60%;
        margin: 0 auto;
        padding: 20px;
        background-color: #f0f5f2;
        border-radius: 10px;
        box-shadow: 0 0 10px #d9e6d9;
        margin-top: 7px;
        padding-left: 100px;
        
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

            <a href="" class="selected">
                <div class="option">
                    <i class="fas fa-home" title="Inicio"></i>
                    <h4>Inicio</h4>
                </div>
            </a>

            <a href="../administrador/contenido/ver_contenidos.php">
                <div class="option">
                    <i class="far fa-file" title="Portafolio"></i>
                    <h4>Material</h4>
                </div>
            </a>
            
            <a href="ver_examen.php">
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

    
    <!--   Tarjetas-->
<div class="title-cards">
<h1> Bienvenido estudiante <?php echo $row['nombre']?></h1><br>
		<h2>¿Que desea hacer hoy?</h2>
	</div>
<div class="container-card">
	
<div class="card">
	<figure>
		<img src="https://grupogeard.com/co/wp-content/uploads/sites/3/2020/07/Blog-saber-11.jpg">
	</figure>
	<div class="contenido-card">
		<h3>Examenes</h3>
		<p>En este item podrás visualizar los examenes disponibles.  </p>
		<a href="ver_examen.php">Ver</a>
	</div>
</div>


<div class="card">
	<figure>
		<img src="https://totumat.files.wordpress.com/2020/03/calificaciones.jpg">
	</figure>
	<div class="contenido-card">
		<h3>Calificaciones</h3>
		<p>En este item podrás visualizar las calificaciones disponibles.  </p>
		<a href="#">Leer Màs</a>
	</div>
</div>
<div class="container">
    <h1>Contenido</h1>
    <ul>
        <?php

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $sql = "SELECT * FROM categorias";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categoria_id = $row["id"];
                $nombre_categoria = $row["nombre"];
                echo "<li><a href='../administrador/contenido/ver_contenidos.php?categoria_id=$categoria_id'>$nombre_categoria</a></li>";
            }
        }
        $conexion->close();
        ?>
    </ul>
</div>
    <script src="assets/js/script.js"></script>
</body>
</html>