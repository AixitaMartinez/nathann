<?php
    session_start();
    include 'conexion_be.php';
    $id = $_POST['id'];
    $contrasena = $_POST['contrasena'];

    // Encriptar contraseña utilizando password_hash
    $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

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
    $stmt = mysqli_prepare($conexion, "SELECT * FROM estudiantes WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    //docentes
    $stmt2 = mysqli_prepare($conexion, "SELECT * FROM docentes WHERE id_cedula = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
    $resultado2 = mysqli_stmt_get_result($stmt2);

   
    //docentes
    if ($resultado2 !== false && mysqli_num_rows($resultado2) > 0) {
        $fila_docente = mysqli_fetch_assoc($resultado2);
        if ($fila_docente["id_cargo"] == 1) {
            $_SESSION['docentes'] = $id;
            header("location: ../administrador/bienvenida_docente.php");
            $usuario_valido = true;
            exit;
        }
    } elseif  ($resultado !== false && mysqli_num_rows($resultado) > 0) {
        $fila_estudiante = mysqli_fetch_assoc($resultado);
        if ($fila_estudiante["id_cargo"] == 2) {
            $_SESSION['estudiantes'] = $id;
            header("location: ../material/bienvenida.php");
            $usuario_valido = true;
            exit;
        }
    } else{
        echo '<script>
        alert("El usuario no existe o la contraseña es incorrecta, por favor verifica los datos introducidos");
        window.location = "../index.php";
        </script>';
    exit;
    }
?>
archivo de bienvenida_docente.php:
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
    echo 'Error en la consulta: ' . mysqli_error($conexion); //  error SQL
    exit;
}
*/
?>
