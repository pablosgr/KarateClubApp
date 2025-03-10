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
            session_start();
            $usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $ruta_i="../../index.php";
            $ruta_soc="../socios";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = ".";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = "../acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);

            //en caso de acceso no permitido, acabo el programa
            if($tipo_sesion != "admin"){
                echo "<section class='productos'><h1>Acceso restringido, necesitas ser Administrador</h1></section>";
                die();
            }
        ?>

        <section class='productos'>
            <h1>Productos</h1>

            <form action="productos.php" method='post' id='buscador'  name='buscar-productos'>
                <input type="text" placeholder='Nombre o precio del producto...' name='query'>
                <span class="error"></span>
                <button type="submit">Buscar</button>
                <a href="productos-add.php" class="btn-add">Añadir producto</a>
            </form>

            <?php

                // RECOJO LOS PARÁMETROS (GET Y POST) PASADOS A LA PÁGINA
                $api_url = "http://localhost/club_karate/api/api.php";
                $api_params = "";
                $num_pagina = "";
                $texto_busqueda = "";
                
                //PARÁMETROS DEL BUSCADOR (POST)
                if(isset($_POST["query"])){
                    $text = trim($_POST["query"]);
                    $texto_busqueda = $text;
                    $array = explode(" ", $text);

                    foreach($array as $item){
                        if(is_numeric($item)){
                            $api_params .= empty($api_params) ? "precioInf=$item" : "&precioInf=$item";
                        } elseif(!empty($item)) {
                            $api_params .= empty($api_params) ? "nombre=$item" : "&nombre=$item";
                        }
                    }

                }

                // PARAMETROS POR URL (GET)
                // if(isset($_GET["pagina"])){
                //     $num_pagina = $_GET['pagina'];
                // }
                    
                $num_pagina = isset($_GET["pagina"]) ? $_GET['pagina'] : 1;

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
                    if(!empty($texto_busqueda)){
                        echo "<h2 class='aviso-prod'>Resultados para \"$texto_busqueda\"</h2>";
                    }
                    echo generarListadoProductos($datos);
                    echo generarPaginadoProductos($datos, $api_params, $num_pagina);
                ?>
            </div>

        </section>

    </main>

    <?php 
        include '../../php/footer.php';
    ?>
</body>
</html>