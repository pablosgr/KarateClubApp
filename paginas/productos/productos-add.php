<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_productos.css">
    <script defer src="../../js/app.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <main class='principal-servicios'>
        <?php
            session_start();
            $usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $ruta_i="../../index.php";
            $ruta_soc="../socios";
            $ruta_serv="servicios.php";
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
            <div class='contenido-productos'>

            <div class='form-container'>
                <form action='productos-confirm.php' method='post' class='formulario-prod mg-top' id='formulario-productos' enctype='multipart/form-data'>
                        <textarea name='nombre' id='' placeholder='Nombre del producto'></textarea>
                        <span class='error'></span>
                        <input type='text' name='precio' id='' placeholder='Precio'>
                        <span class='error'></span>
                        <textarea name='categoria' id='' placeholder='Categoría'></textarea>
                        <span class='error'></span>
                        <input type='text' name='cantidad' id='' placeholder='Cantidad'>
                        <span class='error'></span>
                        <label class='input-file-custom'>
                                <input type='file' name='imagen' id='' accept='image/*'>
                                Subir imágen
                        </label>
                        
                        <input name='tipo' type='hidden' value='add'>
                        <button class='btn btn-outline-secondary' type='submit'>Añadir producto</button>
                </form>
            </div>

            </div>
        </section>

    </main>

    <?php 
        include '../../php/footer.php';
    ?>
</body>
</html>