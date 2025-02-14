<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Citas</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_citas.css">
    <script defer src="../../js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-servicios'>
        <?php
            session_start();
            $usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            require_once '../../php/traduccion.php';

            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="../socios";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="citas.php";
            $ruta_prod = "../productos";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = "../acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);
        ?>

        <section class='citas'>
            <h1>Citas</h1>

            <form action="citas-info.php" method='post' id='buscador' name='buscar-citas'>
                <input type="text" placeholder='Nombre del socio, servicio o fecha' name='texto' id='texto-buscado'>
                <span class="error"></span>
                <button type="submit">Buscar</button>
            </form>

            <?php
                if(isset($_POST["fecha"])){
                    $fecha=$_POST["fecha"];
                    $hora=$_POST["hora"];
                    $id_socio=$_POST["socio"];
                    $id_servicio=$_POST["servicio"];
                    echo generarCita($conexion, $id_socio, $id_servicio, $fecha, $hora);
                }
            ?>
            
            <div class='contenido-citas'>

            <?php
                if(isset($_GET["mes"])){
                    //obtengo el año y mes y le resto 1 al mes
                    $mes_actual=$_GET["mes"]-1;
                    $anno_actual=$_GET["anno"];

                    //si el mes baja de enero, lo reseteo a enero y resto 1 al año
                    if($mes_actual < 1){
                        $mes_actual=12;
                        $anno_actual--;
                    }
                }

                echo imprimirCalendario($conexion, $meses, $mes_actual, $anno_actual);
                echo imprimirFormularioCita($conexion);
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