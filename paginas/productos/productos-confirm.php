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

            <div class='contenido-productos'>
                <?php
                    if(isset($_POST["nombre-prod"])){
                        $id = $_POST["id"];
                        $nombre_new = $_POST["nombre"];
                        $categoria_new = $_POST["categoria"];
                        $precio_new = (float)$_POST["precio"];
                        $cantidad_new = (int)$_POST["cantidad"];
                        $ruta_new  = "";

                        if(isset($_FILES["imagen"]) && $_FILES["imagen"]["size"] > 0){
                            $imagen = $_FILES["imagen"]["name"];
                            $imagen_tmp = $_FILES["imagen"]["tmp_name"];
                            $ruta_new = "../../pics/".$imagen;
                            move_uploaded_file($imagen_tmp, $ruta_new);
                        }

                        $parametros = array(
                            "id" => $id,
                            "nombre" => $nombre_new,
                            "precio" => $precio_new,
                            "categoria" => $categoria_new,
                            "cantidad" => $cantidad_new
                        );

                        if (!empty($ruta_new)) {
                            $parametros["imagen"] = $ruta_new;
                        }

                        $api_url = "http://localhost/club_karate/api/api.php";
                
                        //LLAMADA A LA API CON cURL
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $api_url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parametros));
                        $respuesta = curl_exec($ch);
                        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);
                        $datos = json_decode($respuesta, true);

                        if($http_code != 200){
                            echo "<h2>{$datos['error']}</h2>"; //recupero el mensaje de error de la API
                            header("refresh:3;url=productos.php");
                        } else {
                            echo "<h2>Producto \"{$datos['producto_insertado']}\"actualizado con éxito</h2>";
                            header("refresh:2;url=productos.php");
                        }
                        
                    } else {
                        echo "<h2>Faltan datos para actualizar</h2>";
                        header("refresh:3;url=productos.php");
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