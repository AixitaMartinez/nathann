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
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Inicio</title>
    <script src="https://kit.fontawesome.com/9fd7ac2cb8.js" crossorigin="anonymous"></script>

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
            
            <a href="examen/editar-examen.php">
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

    <!--   Tarjetas-->
<div class="title-cards">
<h1>Bienvenido docente <?php echo $row['nombre']?></h1><br>
		<h2>¿Que desea hacer hoy?</h2>
	</div>
<div class="container-card">
	
<div class="card">
	<figure>
		<img src="https://grupogeard.com/co/wp-content/uploads/sites/3/2020/07/Blog-saber-11.jpg">
	</figure>
	<div class="contenido-card">
		<h3>Examenes</h3>
		<p>En este item podrás crear examenes, visualizar los disponibles, actualizar y eliminar.  </p>
		<a href="examen/editar-examen.php">Editar</a>
	</div>
</div>

<div class="card">
	<figure>
		<img src="https://totumat.files.wordpress.com/2020/03/calificaciones.jpg">
	</figure>
	<div class="contenido-card">
		<h3>Contenido</h3>
		<p>En este item podrás crear contenido para tus estudiantes.  </p>
		<a href="contenido/index_contenido.php">Ver</a>
	</div>
</div>
    

    <script src="../material/assets/js/script.js"></script>
</body>
</html>
