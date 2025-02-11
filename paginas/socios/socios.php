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
            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="#";
            $ruta_serv="../servicios/servicios.php";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = "../productos/productos-cli.php";
            $ruta_dojo = "../dojo/dojo.php";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo);
        ?>

        <section class='socios'>
            <h1>Socios</h1>

            <form action="socios-src.php" method='post' id='buscador'  name='buscar-servicios'>
                <input type="text" placeholder='Nombre o teléfono del socio...' name='texto' id='texto-buscado'>
                <span class="error"></span>
                <button type="submit">Buscar</button>
            </form>

            <?php
                //compruebo si hay datos pasados por post
                if(isset($_POST["nombre_comp"])){
                    $nombre_socio=$_POST["nombre_comp"];
                    $user_socio=$_POST["nombre_user"];
                    $edad_socio=$_POST["edad"];
                    $tlfn_socio=$_POST["tlfn"];
                    $pass_socio=$_POST["pass"];
                    $ruta="../../pics/default.jpg";

                    if(isset($_FILES["foto-avatar"]) && $_FILES["foto-avatar"]["error"] === UPLOAD_ERR_OK){
                        $avatar_socio=$_FILES["foto-avatar"]["name"];
                        $avatar_tmp=$_FILES["foto-avatar"]["tmp_name"];
                        $ruta="../../pics/".$avatar_socio;
                        move_uploaded_file($avatar_tmp, $ruta);
                    }
                    
                    //si los hay, añado al socio antes de mostrar el listado
                    echo añadirSocio($conexion, $nombre_socio, $edad_socio, $pass_socio, $user_socio, $tlfn_socio, $ruta);
                }
            ?>

            <div class='contenido-socios'>

                <?php echo imprimirSociosComp($conexion); ?>

                <div class='tarjeta_socio'>
                    <form action="socios.php" method='post' enctype='multipart/form-data' id='formulario-socios'>
                        <label class="input-file-custom">
                            <input type="file" name='foto-avatar' id="campo-foto" accept="image/*">Subir imágen
                        </label>
                        <span class="error"></span>
                        <input type='text' placeholder='Nombre completo' name='nombre_comp' id="campo-nombre">
                        <span class="error"></span>
                        <input type='text' placeholder='Nombre de usuario' name='nombre_user' id="campo-usuario">
                        <span class="error"></span>
                        <input type='text' placeholder='Edad' name='edad' id="campo-edad">
                        <span class="error"></span>
                        <input type='text' placeholder='Teléfono (+34)' name='tlfn' id="campo-tlfn">
                        <span class="error"></span>
                        <input type='password' placeholder='Contraseña' name='pass' id="campo-pass">
                        <span class="error"></span>
                        <button type='submit'>Añadir socio</button>
                    </form>
                </div>

            </div>
        </section>

    </main>

    <?php 
        include '../../php/footer.php';
        $conexion->close();
    ?>
</body>
</html>