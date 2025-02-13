<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Socios</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_socios.css">
    <script defer src="../js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-servicios'>
        <?php
            session_start();
            $usuario = isset($_SESSION["tipo"]) ? $_SESSION["nombre"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="socios.php";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = "../productos/productos-cli.php";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = "../acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario);
        ?>

        <section class='socios'>
            <h1>Socios</h1>

            <form action="socios-src.php" method='post' id='buscador' name='buscar-servicios'>
                <input type="text" placeholder='Nombre o teléfono del socio...' name='texto' id='texto-buscado'>
                <span class='error'></span>
                <button type="submit">Buscar</button>
            </form>

            <div class='contenido-socios-src'>

                <?php
                if(isset($_POST["texto"])){
                    $texto=$_POST["texto"];
                    echo imprimirSociosBuscados($conexion, $texto);
                }else{
                    echo "<h1 class='centrado'>Error al realizar la búsqueda</h1>";
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