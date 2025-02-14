<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Dojo</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_dojo.css">
    <script defer src="../../js/app.js"></script>
    <script defer src="../../js/public_api.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>
<body>
    <main class='principal-dojo'>
        <?php
            session_start();
            $usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "";
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
            $ruta_dojo = "#";
            $ruta_acc = "../acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);
        ?>

        <section class='dojo'>
            <h1>Dojo</h1>
            <p>
            En nuestro Dojo online, te ofrecemos los <strong>mejores contenidos audiovisuales</strong> sobre kárate, incluyendo técnicas, kumite y katas, para que puedas aprender y practicar desde casa. 
            Elige la temática e idioma que más te interese y, ¡encuentra nuevo contenido <strong>a tu gusto!</strong>
            </p>

            <section class="video-dojo" id="dojo-content">
                
            </section>

            <section class="searchbar-dojo">
                <button class="search-topic" data-value="kumite">Kumite</button>
                <button class="search-topic" data-value="kata">Katas</button>
                <button class="search-topic" data-value="tecnicas">Técnicas</button>
                <button class="search-topic" data-value="español">Español</button>
                <button id="search" class="buscar"><i class="material-symbols-outlined">search</i><span>Buscar</span></button>
            </section>

        </section>

    </main>

    <?php 
        include '../../php/footer.php';
        $conexion->close();
    ?>
</body>
</html>