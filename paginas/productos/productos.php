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
            <h1>Productos</h1>

            <form action="productos.php" method='post' id='buscador'  name='buscar-productos'>
                <input type="text" placeholder='Nombre o precio del producto...' name='query'>
                <span class="error"></span>
                <button type="submit">Buscar</button>
            </form>

            <?php

                // RECOJO LOS PARÁMETROS (GET Y POST) PASADOS A LA PÁGINA
                $api_url = "http://localhost/club_karate/api/api.php";
                $api_params = "";
                $num_pagina = "";
                
                //PARÁMETROS DEL BUSCADOR
                if(isset($_POST["query"])){
                    $text = trim($_POST["query"]);

                    if(is_numeric($text)){
                        $api_params .= empty($api_params) ? "precioInf=$text" : "&precioInf=$text";
                    } elseif(!empty($text)) {
                        $api_params .= empty($api_params) ? "nombre=$text" : "&nombre=$text";
                    }

                }

                // PARAMETROS POR GET
                if(isset($_GET["pagina"])){
                    $num_pagina = $_GET['pagina'];
                }

                if(isset($_GET["precioInf"])){
                    $api_params .=  $api_params == "" ? "precioInf={$_GET['precioInf']}" : "&precioInf={$_GET['precioInf']}";
                }

                if(isset($_GET["nombre"])){
                    $api_params .=  $api_params == "" ? "nombre={$_GET['nombre']}" : "&nombre={$_GET['nombre']}";
                }

                // AGREGO LOS PARÁMETROS EXISTENTES A LA URL
                if(!empty($num_pagina) || !empty($api_params)){
                    if(empty($num_pagina)){
                        $api_url .= "?$api_params";
                    } elseif(empty($api_params)) {
                        $api_url .= "?pagina=$num_pagina";
                    } else {
                        $api_url .= "?pagina=$num_pagina" . "&" . $api_params;
                    }
                }
                
                //LLAMADA A LA API CON cURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json"
                ));
                $respuesta = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $datos = json_decode($respuesta, true);

                if($http_code != 200){
                    echo "<h2>{$datos['error']}</h2>"; //recupero el mensaje de error de la API
                    die();
                }

                curl_close($ch);
            ?>

            <div class='contenido-productos'>
                <?php
                    echo generarListadoProductos($datos);
                    echo generarPaginadoProductos($datos, $api_params, $num_pagina);
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