<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Productos</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_productos.css">
    <script defer src="../../js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <main class='principal-productos'>
        <?php
            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="../socios/socios.php";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit);
        ?>

        <section class='productos'>

            <div class='contenido-productos'>
                <?php
                
                //COMPRUEBO SI SE HA PASADO EL ID POR GET
                if(!isset($_GET['id'])){
                    echo "<h1>No se ha proporcionado una ID</h1>";
                    die();
                }

                $id = $_GET['id'];
                $api_url = "http://localhost/club_karate/api/api.php?id=$id";
                
                //LLAMADA A LA API CON cURL, CON EL ID DESEADO
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json"
                ));
                $respuesta = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $datos = json_decode($respuesta, true);

                if($http_code != 200){
                    echo "<h1>{$datos['error']}</h1>"; //recupero el mensaje de error de la API
                    header("refresh:2;url=productos.php");
                } else {
                    echo "<h1>{$datos['exito']}</h1>";
                    header("refresh:2;url=productos.php");
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