<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shito-Ryu Club | Productos</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/style_productos.css">
    <script defer src="../../js/app.js"></script> <!--Mantener para el menú hamburguesa-->
    <script defer src="../../js/products-api.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>
<body>
    <main class='principal-productos'>
        <?php
            session_start();
            $usuario = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : "";
            $tipo_sesion = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";
            $id_usuario = isset($_SESSION["id_usuario"]) ? $_SESSION["id_usuario"] : "";

            require_once '../../php/funciones.php';
            require_once '../../php/config.php';
            $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
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
        ?>

        <section class='productos'>
            <h1>Productos</h1>

            <div class="add-alert"></div>

            <!-- Carrito -->
            <div class="cart-overlay">
                <aside class="cart">
                    <button class="cart-close">
                        <i class="material-symbols-outlined">close</i>
                    </button>
                    <header>
                        <h3 class="text-slanted">Añadido hasta ahora</h3>
                    </header>
                    
                    <section class="cart-items-list">

                    </section>

                    <footer>
                        <section class="cart-total">
                            <p class="total">Total: <span>0</span> &#8364</p>
                        </section>
                        <section class="footer-buttons">
                            <button class="cart-checkout empty-cart"><i class="material-symbols-outlined">delete</i></button>
                            <button class="cart-checkout place-order">Tramitar pedido</button>
                        </section>
                    </footer>
                </aside>
            </div>

            <section class="form-cart">
                <form action="" method='post' id='buscador'  name='buscar-productos'>
                    <input type="text" placeholder='Nombre o precio del producto...' name='query' id="texto-busqueda">
                    <span class="error"></span>
                    <button type="button" id="btn-buscar">Buscar</button>
                </form>
                <i class='material-symbols-outlined cart-icon'>shopping_cart</i>
            </section>

            <!--Guardo la id del usuario para recuperarla en JS, para guardarla en el carrito. Necesito imprimir la variable con un echo-->
            <input type="hidden" id="id_cliente" value=<?php echo $id_usuario ?>>

            <div class='contenido-productos' id='contenido-productos'>
                
            </div>

        </section>

    </main>

    <?php 
        include '../../php/footer.php';
        $conexion->close();
    ?>
</body>
</html>