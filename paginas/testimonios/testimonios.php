<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Testimonios</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_testimonios.css">
    <script defer src="../../js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-testimonios'>
        <?php
            session_start();
            $usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";
            $id_usuario = isset($_SESSION["id_usuario"]) ? $_SESSION["id_usuario"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="../socios";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="#";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = "../productos";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = "../acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);
        ?>

        <section class='testimonios'>
            <h1>Testimonios</h1>
            <div class='contenido-testimonios'>

                <?php
                    if(isset($_POST["contenido"])){
                        $contenido=$_POST["contenido"];
                        $autor=$_POST["usuario"];
                        añadirTestimonio($conexion, $autor, $contenido);
                    }

                    echo imprimirTestimonios($conexion);

                    if($tipo_sesion != "" && $tipo_sesion != "admin") {
                        echo "
                            <div class='card-testimonio last'>
                                <form action='testimonios.php' method='post' id='formulario-testimonio'>
                                    <textarea name='contenido' id='contenido-testimonio' placeholder='Déjanos tu opinión'></textarea>
                                    <span class='error'></span>
                                    <input type='hidden' value='$id_usuario' id='usuario-testimonio' name='usuario'>
                                    <!--el value se usará para imprimir el nombre del usuario que ha escrito la reseña-->
                                    <button type='submit'>Publicar</button>
                                </form>
                            </div>
                        ";
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