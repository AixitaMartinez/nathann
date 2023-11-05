
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

    <main>

        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Incia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">iniciar sesion</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse"> Regístrarse</button>
                </div>
            </div>
             <!-- Formulario de login y registro-->
            <div class="contenedor__login-register">
                <!--login-->
                <form action="php/login_usuario.php" class="formulario__login" method="POST" >
                    <h2>Iniciar sesión</h2>
                    <input type="text" placeholder="CC/TI" name="id">
                    <input type="password" placeholder="Contraseña" name="contrasena">
                    <button>Entrar</button>
                </form>
                <!--registro-->
                <form action="php/registro_usuario_be.php" method="POST" class="formulario__register">
                    <h2>Registrarse</h2>
                    <input type="text" placeholder="Nombre completo" name="nombre">
                    <input type="text" placeholder="Correo electrónico" name="correo">
                    <input type="text" placeholder="tarjeta de identidad" name="id">
                    <input type="password" placeholder="contraseña" name="contrasena">
                    <select id="grado" name="grado">
                        <option value="1">Undécimo</option>
                        <option value="2">Décimo</option>
                        <option value="3">Noveno</option>
                    </select>
                    <button>Registrarse</button>
                </form>
            </div>
        </div>
    </main>
    <script src="assets/js/script.js"></script>
</body>
</html>