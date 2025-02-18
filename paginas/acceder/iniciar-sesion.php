<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Acceso</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_acceder.css">
    <script defer src="../../js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-acceder'>
        <?php
            session_start();
            $usuario = isset($_SESSION["tipo"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="../socios";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = "../productos";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = ".";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);
        ?>

        <section class='acceder'>
            <h1>Acceder</h1>

            <div class='contenido-acceder'>
                <?php
                    if(isset($_POST["username"]) && isset($_POST["passwd"])){ //comprobar los campos del formulario (acceder.php) en js
                        $usuario = $_POST["username"];
                        $pass = $_POST["passwd"];

                        $sql = "SELECT id, pass, tipo FROM socio WHERE usuario = ?";
                        $consulta = $conexion -> prepare($sql);
                        $consulta -> bind_param("s", $usuario);
                        $consulta -> execute();
                        $consulta -> store_result();

                        if($consulta -> num_rows > 0) {
                            $consulta -> bind_result($id_usuario, $hash_usuario, $tipo_usuario);
                            $consulta -> fetch(); //hago el fetch sin bucle porque sólo devuelve una fila

                            if(password_verify($pass, $hash_usuario)) {
                                //establezco las variables de sesión necesarias para la aplicación
                                $_SESSION["nombre"] = $usuario;
                                $_SESSION["tipo"] = $tipo_usuario;
                                $_SESSION["id_usuario"] = $id_usuario;
                                header("Location:../../index.php"); //lo puedo devolver a la página donde estaba (login) con $_POST[origen]
                            } else {
                                echo "<h2>Contraseña incorrecta, volviendo..</h2>";
                                header("refresh:2;url=acceso.php");
                            }
                        } else {
                            echo "<h2>Usuario no encontrado, volviendo..</h2>";
                            header("refresh:2;url=acceso.php");
                        }

                        $consulta -> close();
                    }
                ?>
            </div>

        </section>

    </main>

    <?php 
        include '../../php/footer.php';
        $conexion->close();
    ?>
</body>
</html>