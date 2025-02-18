<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Socios</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_socios.css">
    <script defer src="../../js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-socios'>
        <?php
            session_start();
            $usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc=".";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = "../productos";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = "../acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);

            //en caso de acceso no permitido, acabo el programa
            if($tipo_sesion != "admin"){
                echo "<section class='socios'><h1>Acceso restringido, necesitas ser Administrador</h1></section>";
                die();
            }
        ?>

        <section class='socios'>

                <?php
                    if(isset($_POST["nombre"])){
                        $id=$_POST["id"];
                        $nombre_new=$_POST["nombre"];
                        $user_new=$_POST["user"];
                        $edad_new=$_POST["edad"];
                        $tlfn_new=$_POST["tlfn"];
                        $ruta_new='';

                        if(isset($_FILES["avatar"]) && $_FILES["avatar"]["size"] > 0){
                            $imagen=$_FILES["avatar"]["name"];
                            $imagen_tmp=$_FILES["avatar"]["tmp_name"];
                            $ruta_new="../../pics/".$imagen;
                            move_uploaded_file($imagen_tmp, $ruta_new);
                        }

                        echo actualizarSocio($conexion, $id, $nombre_new, $user_new, $edad_new, $tlfn_new, $ruta_new);
                    }
                ?>

        </section>

    </main>

    <?php 
        header("refresh:3;url=socios.php");
        include '../../php/footer.php';
        $conexion->close();
    ?>
</body>
</html>