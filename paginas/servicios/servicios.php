<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_servicios.css">
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
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
            $ruta_i="../../index.php";
            $ruta_soc="../socios";
            $ruta_serv="#";
            $ruta_tes="../testimonios/testimonios.php";
            $ruta_not="../noticias/noticias.php";
            $ruta_cit="../citas/citas.php";
            $ruta_prod = "../productos/productos-cli.php";
            $ruta_dojo = "../dojo/dojo.php";
            $ruta_acc = "../acceder";
            echo dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit, $ruta_prod, $ruta_dojo, $ruta_acc, $usuario, $tipo_sesion);
        ?>

        <section class='servicios'>
            <h1>Servicios</h1>

            <?php
                if($tipo_sesion != "") {
                    echo "
                        <form action='servicios-src.php' method='post' id='buscador' name='buscar-servicios'>
                            <input type='text' placeholder='Nombre del servicio...' name='texto' id='texto-buscado'>
                            <span class='error'></span>
                            <button type='submit'>Buscar</button>
                        </form>
                    ";
                }
            ?>

            <div class='contenido-servicios'>

                <?php
                if(isset($_POST["contenido-serv"])){
                    $descripcion=$_POST["contenido-serv"];
                    $duracion=$_POST["duracion"];
                    $ud_duracion=$_POST["u-duracion"];
                    $precio=$_POST["precio"];
                    añadirServicio($conexion, $descripcion, $duracion, $ud_duracion, $precio);
                }

                echo imprimirServiciosComp($conexion, $tipo_sesion);
                
                if($tipo_sesion == "admin"){
                    echo "
                        <div class='card-servicio'>
                            <form action='servicios.php' method='post' id='formulario-servicios'>
                                <textarea name='contenido-serv' id='contenido-servicio' placeholder='Descripción del servicio'></textarea>
                                <span class='error'></span>
                                <input type='text' name='duracion' id='duracion-servicio' placeholder='Duración'>
                                <span class='error'></span>
                                <select name='u-duracion' id='u-duracion-servicio'>
                                    <option value=''>Selecciona una unidad</option>
                                    <option value='minutos'>Minutos</option>
                                    <option value='horas'>Horas</option>
                                </select>
                                <span class='error'></span>
                                <input type='text' name='precio' id='precio-servicio' placeholder='Precio'>
                                <span class='error'></span>
                                <button class='btn btn-outline-secondary' type='submit'>Añadir servicio</button>
                            </form>
                        </div>
                    ";
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