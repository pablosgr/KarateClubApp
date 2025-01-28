<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Dojo</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_dojo.css">
    <script defer src="../../js/public_api.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-dojo'>
        <?php
            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            // require_once '../../vendor/autoload.php'; //lo importo para acceder a las variables de .env
            // use Dotenv\Dotenv;
            
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="../socios/socios.php";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit);

            // cargo el archivo .env a partir del directorio actual (__DIR__) y subiendo dos niveles con dirname()
            // $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            // $dotenv->load();
            // $youtubeApiKey = $_ENV['YOUTUBE_API_KEY']; //accedo a la variable de entorno con la API Key
        ?>

        <section class='dojo'>
            <h1>Dojo</h1>

            <!-- <form action="socios-src.php" method='post' id='buscador'  name='buscar-servicios'>
                <input type="text" placeholder='Nombre o teléfono del socio...' name='texto' id='texto-buscado'>
                <span class="error"></span>
                <button type="submit">Buscar</button>
            </form> -->

            <?php
                
            ?>

            <div class='contenido-dojo'>

            </div>

        </section>

    </main>

    <?php 
        include '../../php/footer.php';
        $conexion->close();
    ?>
</body>
</html>