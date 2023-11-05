<?php
session_start();
include 'conexion_be.php';
$id = $_POST['id'];
$contrasena = $_POST['contrasena'];

// Verificar si los campos obligatorios están vacíos
if (empty($id) || empty($contrasena)) {
    echo '<script>
    alert("Debes completar todos los campos obligatorios");
    window.location = "../index.php";
    </script>';
    exit();
}

// Inicializar una variable de bandera para verificar si se encuentra un usuario válido
$usuario_valido = false;

// estudiantes
$stmt = mysqli_prepare($conexion, "SELECT contraseña FROM estudiantes WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($resultado !== false && mysqli_num_rows($resultado) > 0) {
    $fila_estudiante = mysqli_fetch_assoc($resultado);
    $contrasena_db = $fila_estudiante['contraseña'];
    
    // Verificar la contraseña utilizando password_verify
    if (password_verify($contrasena, $contrasena_db)) {
        $_SESSION['estudiantes'] = $id;
        header("location: ../material/bienvenida.php");
        $usuario_valido = true;
        exit;
    } else{
        echo "<script>
        alert('Contraseña Incorrecta');
        window.location = '../index.php';
        </script>";
        exit;
    }
}


//docentes
$stmt2 = mysqli_prepare($conexion, "SELECT contraseña FROM docentes WHERE id_cedula = ?");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
$resultado2 = mysqli_stmt_get_result($stmt2);


// Verificar si es un docente
if ($resultado2 !== false && mysqli_num_rows($resultado2) > 0) {
    $fila_docente = mysqli_fetch_assoc($resultado2);
    $contrasena_db2 = $fila_docente['contraseña'];
 
   // Verificar la contraseña utilizando password_verify
    if ($contrasena == $contrasena_db2) {
        $_SESSION['docentes'] = $id;
        header("location: ../administrador/bienvenida_docente.php");
        $usuario_valido = true;
        exit;
    } else{
        echo "<script>
        alert('Contraseña Incorrecta');
        window.location = '../index.php';
        </script>";
        exit;
    }
}

// if para cuando no se encuentra el usuario
if (!$usuario_valido) {
    echo "<script>
    alert('Usuario no encontrado');
    window.location = '../index.php';
    </script>";
    exit;
}

?>
