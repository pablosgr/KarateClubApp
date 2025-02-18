<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Inicio</title>
    <link rel="stylesheet" href="css/style.css">
    <script defer src="js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Slackside+One&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-index'>
        <?php
            session_start();
            $usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";

            require_once './php/funciones.php';
            require_once './php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            // include './php/cabecera.php';  para incluir el archivo con cabecera
            //getcwd() devuelve el directorio actual, otra opción
            $ruta_i="#";
            $ruta_soc="./paginas/socios";
            $ruta_serv="./paginas/servicios/servicios.php";
            $ruta_tes="./paginas/testimonios/testimonios.php";
            $ruta_not="./paginas/noticias/noticias.php";
            $ruta_cit="./paginas/citas/citas.php";
            $ruta_prod = "./paginas/productos";
            $ruta_dojo = "./paginas/dojo/dojo.php";
            $ruta_acc = "./paginas/acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);
        ?>

        <section class='banner-index'>
            <p>Club Shito-Ryu</p>
        </section>

        <section class='noticias-index'>
            <h1>Últimas noticias</h1>
            <?php
                echo ultimasNoticias($conexion);
            ?>
        </section>

        <section class='testimonio-index'>
            <?php
                echo testimonioRandom($conexion);
            ?>
        </section>

        <section class='contacto'>
            <form action="" class='form-index'>
                    <input type="text"placeholder="Tu nombre">
                    <input type="text" placeholder="Tu email">
                    <textarea placeholder="¿Qué necesitas?"></textarea>
                    <button type="submit" class='form-index'>Enviar</button>
            </form>
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d12716.721483685087!2d-3.6050490945068354!3d37.17218425249397!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2ses!4v1730391092053!5m2!1ses!2ses" width="500" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>

    </main>

    <?php 
        include './php/footer.php';
        $conexion->close();
    ?>
</body>
</html>