<?php
include 'conexion_be.php';

// Verificar si se enviaron datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $id = $_POST["id"];
    $contrasena = $_POST["contrasena"];
    $grado = $_POST["grado"];

    // Verificar si los campos obligatorios están vacíos
    if (empty($nombre) || empty($correo) || empty($id) || empty($contrasena) || empty($grado)) {
        echo '<script>
        alert("Debes completar todos los campos obligatorios");
        window.location = "../index.php";
        </script>';
        exit();
    }

    // Verificar que el id no se repita en la base de datos
    $verificar_id = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE id='$id'");
    if (mysqli_num_rows($verificar_id) > 0) {
        echo '<script>
        alert("Esta tarjeta de identidad ya está registrada, intenta con otro diferente");
        window.location = "../index.php";
        </script>';
        exit();
    }

    // Verificar correo
    $verificar_correo = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE correo='$correo'");
    if (mysqli_num_rows($verificar_correo) > 0) {
        echo '<script>
        alert("Este correo ya está registrado, intenta con otro diferente");
        window.location = "../index.php";
        </script>';
        exit();
    }

    // Encriptar contraseña
    $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

    // Consulta preparada para el registro
    $stmt = mysqli_prepare($conexion, "INSERT INTO estudiantes(id, nombre, correo, contraseña, id_cargo, id_grado) VALUES(?, ?, ?, ?, ?, ?)");
    $id_cargo = 2; // Cambiar si es necesario
    mysqli_stmt_bind_param($stmt, "isssii", $id, $nombre, $correo, $contrasena_encriptada, $id_cargo, $grado);

    if (mysqli_stmt_execute($stmt)) {
        // Registro exitoso, ahora asigna exámenes al estudiante
        $query_doc = "SELECT id_cedula FROM docentes";
        $resultado_doc = mysqli_query($conexion, $query_doc);
        $docente_id = mysqli_fetch_assoc($resultado_doc);
        $docente_id = $docente_id["id_cedula"];

        // Recorre la lista de exámenes y asígnalos al nuevo estudiante
        $query_es = "SELECT * FROM examenes";
        $examenes = mysqli_query($conexion, $query_es);
        
        while ($examen = mysqli_fetch_assoc($examenes)) {
            $examen_id = $examen['id'];

            // Inserta un registro en la tabla usuarios_examenes para asignar el examen al estudiante
            $query = "INSERT INTO usuarios_examenes (estudiante_id, docente_id, examen_id) 
                    VALUES ($id, $docente_id, $examen_id)";
            $resultado = mysqli_query($conexion, $query);

            if (!$resultado) {
                // Manejar el caso en el que la inserción falle
                echo '<script>
                    alert("Error al asignar un examen al estudiante");
                </script>';
            }
        }

        echo '<script>
            alert("Usuario almacenado exitosamente");
            window.location = "../index.php";
        </script>';
    } else {
        die(mysqli_error($conexion)); // Agrega esta línea para mostrar errores
        echo '<script>
        alert("Error al registrar el usuario. Por favor, inténtelo de nuevo");
        window.location = "../index.php";
        </script>';
    }
    
    mysqli_stmt_close($stmt);
}
?>
