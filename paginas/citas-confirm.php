<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Citas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style_citas.css">
    <script defer src="../js/app_socios.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-citas'>
        <?php
            require_once '../php/funciones.php';
            require_once '../php/config.php';
            require_once '../php/traduccion.php';

            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../index.php";
            $ruta_soc="socios.php";
            $ruta_serv="servicios.php";
            $ruta_tes="testimonios.php";
            $ruta_not="./noticias/noticias.php";
            $ruta_cit="citas.php";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit);
        ?>

        <section class='citas'>
            <div class='contenido-citas-confirm'>
                <?php
                        if(isset($_POST["socio"])){
                            $socio=$_POST["socio"];
                            $servicio=$_POST["servicio"];
                            $fecha=$_POST["fecha"];
                            $hora=$_POST["hora"];
                            $cancel=$_POST["cancel"];
                            $accion=$_POST["action"];
                            //funcion para cancelar o borrar
                        }

                ?>
            </div>

        </section>

    </main>

    <?php
        header("refresh:3;url=citas.php");
        include '../php/footer.php';
        $conexion->close();
    ?>
</body>
</html>