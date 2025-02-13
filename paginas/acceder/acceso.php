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
            
            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $ruta_i="../../index.php";
            $ruta_soc="../socios/socios.php";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = "../productos/productos-cli.php";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = ".";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario);
        ?>

        <section class='acceder'>
            <h1>Acceder</h1>

            <div class='contenido-acceder'>
                <form action="iniciar-sesion.php" method="post" class="login-form">
                    <input type="text" name="username" id="" class="login-name" placeholder="Nombre de usuario">
                    <input type="password" name="passwd" id="" class="login-pass" placeholder="Contraseña">
                    <input type="hidden" name="origen" value="<?php basename($_SERVER['PHP_SELF']) ?>">
                    <button type="submit" class="login-submit">Iniciar Sesión</button>
                </form>
            </div>

        </section>

    </main>

    <?php 
        include '../../php/footer.php';
    ?>
</body>
</html>