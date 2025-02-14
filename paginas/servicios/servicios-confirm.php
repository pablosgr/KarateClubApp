<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Servicios</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_servicios.css">
    <script defer src="../../js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-servicios'>
        <?php
            session_start();
            $usuario = isset($_SESSION["tipo"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="../socios";
            $ruta_serv="servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = "../productos/productos-cli.php";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = "../acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);

            //en caso de acceso no permitido, acabo el programa
            if($tipo_sesion == ""){
                echo "<section class='servicios'><h1>Acceso restringido</h1></section>";
                die();
            }
        ?>

        <section class='servicios'>

            <div class='contenido-servicios'>

                <?php
                    if(isset($_POST["contenido-serv"])){
                        $id=$_POST["id"];
                        $descripcion=$_POST["contenido-serv"];
                        $duracion=$_POST["duracion"];
                        $ud_duracion=$_POST["u-duracion"];
                        $precio=$_POST["precio"];
                        echo actualizarServicio($conexion, $id, $descripcion, $duracion, $ud_duracion, $precio);
                    }

                ?>

            </div>
        </section>

    </main>

    <?php 
        header("refresh:3;url=servicios.php");
        include '../../php/footer.php';
        $conexion->close();
    ?>
</body>
</html>