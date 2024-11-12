<?php
//conexión con base de datos
    function conectar($host, $usuario, $password, $base_datos){
        $conexion = new mysqli($host, $usuario, $password, $base_datos);
        $conexion->set_charset("utf8");
        return $conexion;
    }

//función para cabecera
    function dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit){
        $resultado='';
        $resultado.="
        <header>
        <h1><a href='$ruta_i'>糸東流</a></h1>
            <nav>
                <ul>
                    <li><a href='$ruta_soc'>SOCIOS</a></li>
                    <li><a href='$ruta_serv'>SERVICIOS</a></li>
                    <li><a href='$ruta_tes'>TESTIMONIOS</a></li>
                    <li><a href='$ruta_not'>NOTICIAS</a></li>
                    <li><a href='$ruta_cit'>CITAS</a></li>
                </ul>
            </nav>
        </header>";

        return $resultado;
    }

//muestra de datos con consultas - index
    function testimonioRandom($conexion){
        $resultado='';
        $sql='SELECT contenido,nombre FROM testimonio
            JOIN socio ON socio.id=testimonio.autor
            ORDER BY RAND() LIMIT 1
        ';

        $sql_result=$conexion->query($sql);
        while($row=$sql_result->fetch_array(MYSQLI_ASSOC)){
            $testimonio=$row["contenido"];
            $nombre_usuario=$row["nombre"];
            $resultado.="
                <h3>$nombre_usuario</h3>
                <p><em>$testimonio</em></p>
            ";
        }
        return $resultado;
    }

    function ultimasNoticias($conexion){
        $resultado='';
        $sql='SELECT titulo,contenido,imagen,fecha_publicacion
        FROM noticia ORDER BY fecha_publicacion DESC LIMIT 3';

        $resultado.=generarNoticias($sql, $resultado, $conexion);

        return $resultado;
    }

//funciones socios
    function imprimirSocios($conexion){
        $resultado='';
        $sql='SELECT id,nombre,usuario,edad,telefono,foto FROM socio';

        $sql_result=$conexion->query($sql);
        while($row=$sql_result->fetch_array(MYSQLI_ASSOC)){
            $id=$row["id"];
            $nombre=$row["nombre"];
            $usuario=$row["usuario"];
            $edad=$row["edad"];
            $tlfn=$row["telefono"];
            $ruta_avatar=$row["foto"];

            $resultado.="
                <div class='tarjeta_socio'>
                    <div class='avatar'><img src='$ruta_avatar'></div>
                    <h3>$nombre</h3>
                    <p>Usuario: $usuario</p>
                    <p>Edad: $edad</p>
                    <p>Tlfn: $tlfn</p>
                    <a href='socios-mod.php?id=$id' class='boton'>Modificar</a>
                </div>
            ";
        }
        return $resultado;
    }


    function imprimirModificarSocio($conexion, $id){
        $resultado='';
        $sql='SELECT nombre,usuario,edad,telefono,foto FROM socio WHERE id=?';

        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $consulta->bind_result($nombre_r, $usuario_r, $edad_r, $telefono_r, $foto_r);

        while($consulta->fetch()){
            $resultado.="<div class='tarjeta_socio'>
                <form action='socios-confirm.php' method='post' id='formulario-mod'>
                    <div class='avatar'><img src='$foto_r'></div>
                    <input type='text' value='$nombre_r' name='nombre' placeholder='Nombre completo' id='nombre-mod'>
                    <span class='error'></span>
                    <input type='text' value='$usuario_r' name='user' placeholder='Nombre de usuario' id='user-mod'>
                    <span class='error'></span>
                    <input type='text' value='$edad_r' name='edad' placeholder='Edad' id='edad-mod'>
                    <span class='error'></span>
                    <input type='text' value='$telefono_r' name='tlfn' placeholder='Teléfono' id='tlfn-mod'>
                    <span class='error'></span>
                    <input type='hidden' value='$id' name='id'>
                    <button type='submit'>Actualizar socio</button>
                </form>
            </div>
            ";
        }

        $consulta->close();
        return $resultado;
    }


    function añadirSocio($conexion, $nombre, $edad, $pass, $usuario, $tlfn, $ruta_img){
        $sql='INSERT INTO socio (nombre, edad, pass, usuario, telefono, foto) 
        VALUES (?, ?, ?, ?, ?, ?)';
        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("sissss", $nombre, $edad, $pass, $usuario, $tlfn, $ruta_img);
        $consulta->execute();
        $consulta->close();
    }

    function actualizarSocio($conexion, $id, $nombre, $usuario, $edad, $telefono){
        $resultado="";
        $sql='UPDATE socio SET nombre=?, usuario=?, edad=?, telefono=? WHERE id=?';
        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("ssiii", $nombre, $usuario, $edad, $telefono, $id);
        $consulta->execute();

        if($consulta){
            $resultado.="<h1 class='centrado'>Usuario actualizado</h1>
            <h2 class='centrado'>Volviendo a la página de socios en 3 segundos...</h2>";
        }else{
            $resultado.="<h1 class='centrado'>Error</h1>";
        }

        $consulta->close();
        return $resultado;
    }

//funciones testimonios

function imprimirTestimonios($conexion){
    $resultado='';
        $sql='SELECT testimonio.id,nombre,contenido,fecha FROM socio
        JOIN testimonio ON socio.id=testimonio.autor
        ORDER BY fecha DESC';

        $sql_result=$conexion->query($sql);
        while($row=$sql_result->fetch_array(MYSQLI_ASSOC)){
            $nombre=$row["nombre"];
            $texto=$row["contenido"];
            $fecha=$row["fecha"];

            $resultado.="
                <div class='card-testimonio'>
                    <h2>$nombre</h2>
                    <p>$texto</p>
                    <p>$fecha</p>
                </div>
            ";
        }
        return $resultado;
}


//funciones internas
    function generarNoticias($sql, $resultado, $conexion){
        $sql_result=$conexion->query($sql);

        while($row=$sql_result->fetch_array(MYSQLI_ASSOC)){
            $titulo=$row["titulo"];
            $contenido=substr($row["contenido"], 0, 75);
            $ruta_imagen=$row["imagen"];
            $fecha=$row["fecha_publicacion"];
            $resultado.="<article class='noticia-index'>
                <h2>$titulo</h2>
                <div class='contenido-noticia'>
                    <img src='$ruta_imagen'>
                    <div class='side-text'>
                        <p>$contenido...</p>
                        <p>$fecha</p>
                    </div>
                </div>
            </article>";
        }

        return $resultado;
    }
?>