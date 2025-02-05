<?php
//conexión con base de datos ----------------------------------------------------------------

    function conectar($host, $usuario, $password, $base_datos){
        $conexion = new mysqli($host, $usuario, $password, $base_datos);
        $conexion->set_charset("utf8");
        return $conexion;
    }

//función para cabecera ----------------------------------------------------------------

    //dibuja la cabecera con las rutas pasadas por parámetro
    function dibujarCabecera($ruta_i, $ruta_soc, $ruta_serv, $ruta_tes, $ruta_not, $ruta_cit){
        $resultado='';
        $resultado.="
        <header>
        <h1><a href='$ruta_i'>糸東流</a></h1>
            <nav>
                <ul>
                    <li><a href='$ruta_not'>NOTICIAS</a></li>
                    <li><a href='$ruta_serv'>SERVICIOS</a></li>
                    <li><a href=''>PRODUCTOS</a></li>
                    <li><a href=''>DOJO</a></li>
                    <li><a href='$ruta_tes'>TESTIMONIOS</a></li>
                    <li><a href='$ruta_soc'>SOCIOS</a></li>
                    <li><a href='$ruta_cit'>CITAS</a></li>
                    <li><a href=''><img src='../../pics/user.png' class='login-img'></a></li>
                </ul>
            </nav>
        <button id='menu-btn' aria-label='Toggle menu'>&#9776;</button>
        </header>";

        return $resultado;
    }

//función para paginado ----------------------------------------------------------------

    function imprimirPaginado($conexion, $pagina){
        $resultado='<ul>';
        $total='';
        $sql='SELECT COUNT(id) AS total FROM noticia';
        
        $consulta=$conexion->prepare($sql);
        $consulta->execute();
        $consulta->bind_result($total);
        while($consulta->fetch()){
            $total=intval($total); // convierto a entero
        }
        $consulta->close();

        $num_paginas=round($total/4); //obtengo el numero de paginas a imprimir basado en el nº de consultas en la bd. ceil redondea siempre hacia arriba, floor hacia abajo, round estándar

        for($i=1; $i<=$num_paginas; $i++){
            if($pagina == $i){
                $resultado.="<a href='noticias.php?pagina=$i'><li class='marcado'>$i</li></a>"; //si esta en la página actual, lo marco
            }else{
                $resultado.="<a href='noticias.php?pagina=$i'><li>$i</li></a>"; //imprimo un li por cada 4 noticias
            }
        }
        $resultado.='</ul>';

        return $resultado;
    }

//funciones index ----------------------------------------------------------------

    function testimonioRandom($conexion){
        $resultado='';
        $sql='SELECT contenido,nombre FROM testimonio
            JOIN socio ON socio.id=testimonio.autor
            ORDER BY RAND() LIMIT 1
        ';

        $consulta=$conexion->prepare($sql);
        $consulta->execute();
        $consulta->bind_result($testimonio, $nombre_usuario);
        while($consulta->fetch()){
            $resultado.="
                <h3>$nombre_usuario</h3>
                <p><em>$testimonio</em></p>
            ";
        }
        $consulta->close();
        
        return $resultado;
    }

    function ultimasNoticias($conexion){
        $sql = 'SELECT id, titulo, contenido, imagen, fecha_publicacion
        FROM noticia
        WHERE fecha_publicacion <= CURDATE()
        ORDER BY fecha_publicacion DESC, id DESC
        LIMIT 3';
        //ordenará por fecha y por hora
        $ruta_index=true;

        $resultado=generarListaNoticias($sql, $conexion, $ruta_index); //llama a la funcion que genera las noticias listadas

        return $resultado;
    }

//funciones noticias ----------------------------------------------------------------

    function imprimirNoticias($conexion, $pagina){
        $offset = ($pagina - 1) * 4; //para que calcule el offset de 4 en 4 según el número de página empezando en 0 (las noticias de cada página)
        $sql="SELECT id,titulo,contenido,imagen,fecha_publicacion
        FROM noticia 
        WHERE fecha_publicacion <= CURDATE()
        ORDER BY fecha_publicacion DESC, id DESC
        LIMIT 4 OFFSET $offset";
        $ruta_index=false;

        $resultado=generarListaNoticias($sql, $conexion, $ruta_index); //llama a la funcion que genera las noticias listadas

        return $resultado;
    }

    //genera la noticia completa
    function generarNoticia($conexion, $id){
        $resultado='';
        $sql="SELECT * FROM noticia WHERE id=?";
        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $consulta->store_result(); 
        $consulta->bind_result($id, $titulo, $contenido, $imagen, $fecha_publicacion);
        while($consulta->fetch()){
            $resultado.="
                <h1>$titulo</h1>
                <img src='$imagen'>
                <p>$contenido</p>
                <p>$fecha_publicacion</p>
            ";
        }
        $consulta->close();

        return $resultado;
    }

    function añadirNoticia($conexion, $imagen, $titulo, $contenido, $fecha){
        $resultado='';
        $sql='INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion)
            VALUES (?, ?, ?, ?)';
    
        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("ssss", $titulo, $contenido, $imagen, $fecha);
        $consulta->execute();

        if($consulta){
            $resultado.="<h1 class='centrado'>Noticia publicada</h1>
            <h2 class='centrado'>Volviendo a la página de noticias en 3 segundos...</h2>";
        }else{
            $resultado.="<h1 class='centrado'>Error</h1>";
        }
        return $resultado;
        $consulta->close();
    }

//funciones socios ----------------------------------------------------------------

    //imprime todos los socios de la bd en formato card
    function imprimirSociosComp($conexion){
        $resultado='';
        $sql='SELECT id,nombre,usuario,edad,telefono,foto FROM socio';

        $consulta=$conexion->prepare($sql);
        $consulta->execute();
        $consulta->bind_result($id, $nombre, $usuario, $edad, $tlfn, $ruta_avatar);
        while($consulta->fetch()){
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
        $consulta->close();

        return $resultado;
    }

    function imprimirSociosBuscados($conexion, $texto){
        $texto="%".$texto."%"; //al usarse en un LIKE, añado a la variable los % directamente
        $sql="SELECT id,nombre,usuario,edad,telefono,foto FROM socio
        WHERE nombre LIKE ?
        OR telefono LIKE ?
        ORDER BY id ASC";
        $resultado='';

        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("ss", $texto, $texto);
        $consulta->execute();
        $consulta->store_result(); // Necesario para contar filas que devuelve la consulta
        $consulta->bind_result($id, $nombre, $usuario, $edad, $telefono, $foto);

        if($consulta -> num_rows > 0){
            while($consulta->fetch()){
                $resultado.="
                    <div class='tarjeta_socio'>
                        <div class='avatar'><img src='$foto'></div>
                        <h3>$nombre</h3>
                        <p>Usuario: $usuario</p>
                        <p>Edad: $edad</p>
                        <p>Tlfn: $telefono</p>
                        <a href='socios-mod.php?id=$id' class='boton'>Modificar</a>
                    </div>
                ";
            }
        }else{
            $resultado.="
                <h1 class='centrado'>No se han encontrado resultados...</h1>
            ";
        }
        return $resultado;
    }

    //imprime el formulario de modificacion del socio
    function imprimirModificarSocio($conexion, $id){
        $resultado='';
        $sql='SELECT nombre,usuario,edad,telefono,foto FROM socio WHERE id=?'; //recojo los datos del socio pasado por id para cargarlos por defecto

        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $consulta->bind_result($nombre_r, $usuario_r, $edad_r, $telefono_r, $foto_r);

        while($consulta->fetch()){
            $resultado.="<div class='tarjeta_socio'>
                <form action='socios-confirm.php' method='post' id='formulario-socios' enctype='multipart/form-data'>
                    <div class='avatar'><img src='$foto_r'></div>
                    <label class='input-file-custom'>
                            <input type='file' name='avatar' id='campo-foto' accept='image/*'>
                            Subir imágen
                    </label>
                    <span class='error'></span>
                    <input type='text' value='$nombre_r' name='nombre' placeholder='Nombre completo' id='campo-nombre'>
                    <span class='error'></span>
                    <input type='text' value='$usuario_r' name='user' placeholder='Nombre de usuario' id='campo-usuario'>
                    <span class='error'></span>
                    <input type='text' value='$edad_r' name='edad' placeholder='Edad' id='campo-edad'>
                    <span class='error'></span>
                    <input type='text' value='$telefono_r' name='tlfn' placeholder='Teléfono' id='campo-tlfn'>
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
        $resultado='';
        //compruebo con dos consultas que el telefono y ususario no estén ya en uso
        $check='SELECT * FROM socio WHERE telefono = ? OR usuario = ?';

        $consulta_check=$conexion->prepare($check);
        $consulta_check->bind_param("ss", $tlfn, $usuario);
        $consulta_check->execute();
        $consulta_check->store_result(); 
        if($consulta_check->num_rows > 0){
            //de estar en uso, imprimo mensaje de error
            $resultado.="<h2 class='centrado red'>El teléfono o nombre de usuario ya están en uso</h2>";
            $consulta_check->close();
            return $resultado;
        }
        $consulta_check->close();

        //si no están en uso, continuo con el INSERT
        $sql='INSERT INTO socio (nombre, edad, pass, usuario, telefono, foto) 
        VALUES (?, ?, ?, ?, ?, ?)';
        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("sissss", $nombre, $edad, $pass, $usuario, $tlfn, $ruta_img);
        $consulta->execute();
        $id_insertado = $consulta -> insert_id;
        $consulta->close();

        generarApiKey($conexion, $id_insertado);

        return $resultado;
    }


    function actualizarSocio($conexion, $id, $nombre, $usuario, $edad, $telefono, $ruta){
        $resultado='';

        $consulta_check="SELECT * FROM socio WHERE (telefono=? OR usuario=?) AND nombre!=?"; //compruebo si hay alguien con datos iguales que no sea el propio usuario
        $consulta_check=$conexion->prepare($consulta_check);
        $consulta_check->bind_param("sss", $telefono, $usuario, $nombre);
        $consulta_check->execute();
        $consulta_check->store_result(); 

        if($consulta_check->num_rows > 0){
            $consulta_check->close();
            $resultado.="<h2 class='centrado red'>El usuario o teléfono ya están en uso</h2>
            <h2 class='centrado'>Volviendo a la página de socios en 3 segundos...</h2>";
            return $resultado;
        }
        $consulta_check->close();

        if($ruta === ''){
            //compruebo si la foto se ha actualizado o no
            $sql='UPDATE socio SET nombre=?, usuario=?, edad=?, telefono=? WHERE id=?';
            $consulta=$conexion->prepare($sql);
            $consulta->bind_param("ssisi", $nombre, $usuario, $edad, $telefono, $id);
        }else{
            //si la foto se ha actualizado, la incluyo en el UPDATE
            $sql='UPDATE socio SET nombre=?, usuario=?, edad=?, telefono=?, foto=? WHERE id=?';
            $consulta=$conexion->prepare($sql);
            $consulta->bind_param("ssissi", $nombre, $usuario, $edad, $telefono, $ruta, $id);
        }
        
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

//funciones testimonios ----------------------------------------------------------------

function imprimirTestimonios($conexion){
    $resultado='';
        $sql='SELECT testimonio.id,nombre,contenido,fecha FROM socio
        JOIN testimonio ON socio.id=testimonio.autor
        ORDER BY fecha DESC';

    $consulta=$conexion->prepare($sql);
    $consulta->execute();
    $consulta->bind_result($id, $nombre, $texto, $fecha);

    while($consulta->fetch()){
        $resultado.="
            <div class='card-testimonio'>
                <h2>$nombre</h2>
                <p>$texto</p>
                <p>$fecha</p>
            </div>
        ";
    }
    $consulta->close();

    return $resultado;
}

function añadirTestimonio($conexion, $autor, $contenido){
    $fecha_actual = date("Y-m-d");
    $sql='INSERT INTO testimonio (autor, contenido, fecha)
        VALUES (?, ?, ?)';

    $consulta=$conexion->prepare($sql);
    $consulta->bind_param("iss", $autor, $contenido, $fecha_actual);
    $consulta->execute();
    $consulta->close();
}

//funciones servicios ----------------------------------------------------------------

function imprimirServiciosComp($conexion){
    $sql='SELECT id, descripcion, duracion, unidad_duracion, precio FROM servicio
    ORDER BY descripcion ASC';

    $resultado=imprimirServicios($conexion, $sql);
    return $resultado;
}


function imprimirServiciosBuscados($conexion, $texto){
    $texto="%".$texto."%";
    $sql="SELECT id, descripcion, duracion, unidad_duracion, precio FROM servicio
    WHERE descripcion LIKE ?
    ORDER BY descripcion ASC";

    $resultado=imprimirServiciosPrep($conexion, $sql, $texto);
    return $resultado;
}


function imprimirModificarServicio($conexion, $id){
    $resultado='';
    $sql='SELECT descripcion, duracion, precio FROM servicio
    WHERE id=?';

    $consulta=$conexion->prepare($sql);
    $consulta->bind_param("i", $id);
    $consulta->execute();
    $consulta->bind_result($descripcion_r, $duracion_r, $precio_r);

    while($consulta->fetch()){
        $resultado.="
        <div class='card-servicio'>
            <form action='servicios-confirm.php' method='post' id='formulario-servicios'>
                    <textarea name='contenido-serv' id='contenido-servicio' placeholder='Descripción del servicio'>$descripcion_r</textarea>
                    <span class='error'></span>
                    <input type='text' name='duracion' id='duracion-servicio' value='$duracion_r' placeholder='Duración'>
                    <span class='error'></span>
                    <select name='u-duracion' id='u-duracion-servicio'>
                        <option value=''>Selecciona una unidad</option>
                        <option value='minutos'>Minutos</option>
                        <option value='horas'>Horas</option>
                    </select>
                    <span class='error'></span>
                    <input type='text' name='precio' id='precio-servicio' value='$precio_r' placeholder='Precio'>
                    <span class='error'></span>
                    <input name='id' type='hidden' value='$id'>
                    <button class='btn btn-outline-secondary' type='submit'>Actualizar servicio</button>
            </form>
        </div>
        ";
    }

    $consulta->close();
    return $resultado;
}

function añadirServicio($conexion, $descripcion, $duracion, $ud_duracion, $precio){
    $sql='INSERT INTO servicio (descripcion, duracion, unidad_duracion, precio)
        VALUES (?, ?, ?, ?)';

    $consulta=$conexion->prepare($sql);
    $consulta->bind_param("sisi", $descripcion, $duracion, $ud_duracion, $precio);
    $consulta->execute();
    $consulta->close();
}

function actualizarServicio($conexion, $id, $descripcion, $duracion, $ud_duracion, $precio){
    $resultado="";
    $sql='UPDATE servicio SET descripcion=?, duracion=?, unidad_duracion=?, precio=? WHERE id=?';
    $consulta=$conexion->prepare($sql);
    $consulta->bind_param("sisii", $descripcion, $duracion, $ud_duracion, $precio, $id);
    $consulta->execute();

    if($consulta){
        $resultado.="<h1 class='centrado'>Servicio actualizado</h1>
        <h2 class='centrado'>Volviendo a la página de servicios en 3 segundos...</h2>";
    }else{
        $resultado.="<h1 class='centrado'>Error</h1>";
    }

    $consulta->close();
    return $resultado;
}

//funciones citas ----------------------------------------------------------------
    
function imprimirCalendario($conexion, $meses, $mes_actual, $anno_actual){
    $mes_actual = sprintf("%02d", $mes_actual); //formateo de mes para que tenga dos digitos (necesario)
    $dias_mes=cal_days_in_month(CAL_GREGORIAN, $mes_actual, $anno_actual); //función para obtener el número de días del mes
    $contador_semana=7;
    $dias=['Lun', 'Mar', 'Miér', 'Jue', 'Vie', 'Sáb', 'Dom']; //guardo los nombres de los días para imprimir la cabecera automáticamente
    $act_date=date("Y-m-d"); //obtendo la fecha actual

    $primer_dia=mktime(0, 0, 0, $mes_actual, 1, $anno_actual); // crear una marca de tiempo para el primer día del mes
    $pos_semana=date("N", $primer_dia); // obtener el día de la semana del primer día del mes (en número)

    $array_fechas=[]; //aqui guardaré toda slas fechas de la bd
    $sql='SELECT fecha FROM citas'; //también podría lanzar una consulta al imprimir cada día (si hubiera un gran número de citas)
    
    $consulta=$conexion->prepare($sql);
    $consulta->execute();
    $consulta->bind_result($f);
    while($consulta->fetch()){
        array_push($array_fechas, $f); //incluyo todas las fechas de citas de la db en el array
    }
    $consulta->close();

    //uso el archivo traduccion.php para acceder al array $meses y obtener los nombres de los meses en español
    $resultado="
        <div class='cal'>
        <div class='contenedor-cal'>
            <h2>$meses[$mes_actual] de $anno_actual</h2>
            <div class='seleccion'>
                <a href='citas-prv.php?mes=$mes_actual&anno=$anno_actual' class='btn'>Anterior</a>
                <a href='citas-nxt.php?mes=$mes_actual&anno=$anno_actual' class='btn'>Siguiente</a>
            </div>
        </div>
        <table class='calendario'>
        <thead>
    ";
    foreach($dias as $d){
        $resultado.="<th>$d</th>";
    }
    $resultado.="</thead>
        <tbody>
        <tr>
    ";

    for($i=1; $i<=$dias_mes; $i++){ //for para imprimir todos los dias del mes
        if($pos_semana > 1){
            $resultado.="<td></td>"; //añado celda vacía por cada día que no coincida con el lunes (1)
            $pos_semana--; //le resto 1 a la posicion de la semana para volver a comprobar
            $i--; //resto 1 a la variable del for para que no avance en los días del mes
        }else{
            $dia_format=sprintf("%02d", $i); //formateo el día a dos digitos
            $fecha="$anno_actual-$mes_actual-$dia_format";
            //compruebo que la fecha esté presente en el array de fechas para marcar el día
            if(in_array($fecha, $array_fechas)){
                //compruebo que la fecha esté en el array para marcar el día (tiene citas)
                $resultado.="
                <td class='dia-selecc'>
                    <a href='citas-info.php?fecha=$fecha'>";
                    if($fecha === $act_date){
                        $resultado.="<div class='celda green actual'>$i</div>";
                    }else{
                        $resultado.="<div class='celda green'>$i</div>";
                    }

                $resultado.="</a>
                    </td>
                ";
            }else{
                $resultado.="
                <td class='dia-selecc'>
                    <a href='citas-info.php?fecha=$fecha'>";
                
                    if($fecha === $act_date){
                        $resultado.="<div class='celda actual'>$i</div>";
                    }else{
                        $resultado.="<div class='celda'>$i</div>";
                    }

                $resultado.="</a>
                    </td>
                ";
            }
        }
        
        if($contador_semana > 1){
            $contador_semana--;
        }else{
            $resultado.="</tr><tr>"; //cierro y abro fila
            $contador_semana=7; //reseteo el contador de dias a 7
        }
    }

    $resultado.="</tr>
        </tbody>
        </table>
        </div>
    ";

    return $resultado;
    
}

function imprimirFormularioCita($conexion){
    $sql_socios='SELECT id,nombre FROM socio';
    $sql_servicios='SELECT id,descripcion FROM servicio';
    
    $consulta1=$conexion->prepare($sql_socios);
    $consulta1->execute();
    $consulta1->bind_result($id, $nombre);
    $resultado="
        <div class='form-cita'>
            <form id='form-citas' action='citas.php' method='post' id='form-citas'>
                <p>Nueva cita</p>
                <input type='date' name='fecha' id='fecha-cita'>
                <span class='error'></span>
                <input type='time' name='hora' id='hora-cita'>
                <span class='error'></span>
                <select name='socio' id='socio-cita'>
                <option value=''>Selecciona un socio</option>
    ";
    while($consulta1->fetch()){
        $resultado.="
            <option value='$id'>$nombre</option>
        ";
    }
    $consulta1->close();

    $consulta2=$conexion->prepare($sql_servicios);
    $consulta2->execute();
    $consulta2->bind_result($id_s, $nombre_s);
    $resultado.="</select>
        <span class='error'></span>
        <select name='servicio' id='servicio-cita'>
        <option value=''>Selecciona un servicio</option>
    ";
    while($consulta2->fetch()){
        $resultado.="
            <option value='$id_s'>$nombre_s</option>
        ";
    }
    $consulta2->close();

    $resultado.="</select>
        <span class='error'></span>
        <div class='caja-btn'>
            <button type='submit'>Generar cita</button>
        </div>
        </form>
        </div>
    ";

    return $resultado;
}

function generarCita($conexion, $socio, $servicio, $fecha, $hora){
    $resultado='';

    //lanzo una consulta para comprobar que no haya duplicidades no deseadas
    $check="SELECT * FROM citas WHERE socio=? AND servicio=? AND fecha=? AND hora=?";
    $consulta_check=$conexion->prepare($check);
    $consulta_check->bind_param("iiss", $socio, $servicio, $fecha, $hora);
    $consulta_check->execute();
    $consulta_check->store_result();
    if($consulta_check->num_rows === 0){
        //si no hay duplicados, inserto la cita
        $sql='INSERT INTO citas (socio, servicio, fecha, hora) 
        VALUES (?, ?, ?, ?)';
        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("iiss", $socio, $servicio, $fecha, $hora);
        $consulta->execute();
        $consulta->close();
    }else{
        //retorno un mensaje de error en caso de duplicidad
        $resultado.="<h2 class='centrado red'>Error al generar la cita (duplicidad en los datos)</h2>";
    }
    $consulta_check->close();
    return $resultado;
}

function modificarCita($conexion, $socio, $servicio, $fecha, $hora, $cancel, $accion){
    $resultado='';
    $sqld='DELETE FROM citas WHERE socio=? AND servicio=? AND fecha=? and hora=?';
    $sqlu='UPDATE citas SET cancelada=1 WHERE socio=? AND servicio=? AND fecha=? and hora=?';
    
    $fecha_actual=date("Y-m-d");
    $fecha_obj = new DateTime($fecha);
    $fecha_actual_obj = new DateTime($fecha_actual);

    //paso la accion a realizar (delete o update) por una variable 'accion' en GET y, según su valor, ejecuto una u otra consulta
    if ($fecha_obj > $fecha_actual_obj){
        if($accion==='d'){
            $consulta=$conexion->prepare($sqld);
            $consulta->bind_param("iiss", $socio, $servicio, $fecha, $hora);
            $consulta->execute();
            $consulta->close();
            $resultado.="<h1 class='centrado'>Cita borrada</h1>
            <h2 class='centrado'>Volviendo a la página de citas en 3 segundos...</h2>";
        }else if($accion==='c'){
            $consulta=$conexion->prepare($sqlu);
            $consulta->bind_param("iiss", $socio, $servicio, $fecha, $hora);
            $consulta->execute();
            $consulta->close();
            $resultado.="<h1 class='centrado'>Cita cancelada</h1>
            <h2 class='centrado'>Volviendo a la página de citas en 3 segundos...</h2>";
        }
    }else{
        $resultado.="<h2 class='centrado red'>No se pueden cancelar o borrar citas anteriores o iguales al día actual</h2>
        <h2 class='centrado'>Volviendo a la página de citas en 3 segundos...</h2>";
    }

    return $resultado;
}

function imprimirCitas($conexion, $fecha){
    $sql='SELECT socio.nombre,servicio.descripcion,socio,servicio,fecha,hora,cancelada 
    FROM citas 
    JOIN servicio ON servicio.id=citas.servicio
    JOIN socio ON socio.id=citas.socio
    WHERE fecha=?';

    $consulta=$conexion->prepare($sql);
    $consulta->bind_param("s", $fecha);
    $consulta->execute();
    $consulta->store_result(); 
    $consulta->bind_result($nombre_socio, $nombre_servicio, $id_socio, $id_servicio, $fecha_cita, $hora, $cancel);

    $resultado=listaCitas($consulta);

    return $resultado;
}

function imprimirCitasBuscadas($conexion, $texto){
    $texto="%".$texto."%";
    $sql='SELECT socio.nombre,servicio.descripcion,socio,servicio,fecha,hora,cancelada 
    FROM citas 
    JOIN servicio ON servicio.id=citas.servicio
    JOIN socio ON socio.id=citas.socio
    WHERE socio.nombre LIKE ?
    OR servicio.descripcion LIKE ?
    OR fecha LIKE ?
    ORDER BY fecha DESC';

    $consulta=$conexion->prepare($sql);
    $consulta->bind_param("sss", $texto, $texto, $texto);
    $consulta->execute();
    $consulta->store_result();

    $resultado=listaCitas($consulta);

    return $resultado;
}

//funciones internas ----------------------------------------------------------------
    
    function generarListaNoticias($sql, $conexion, $ruta_index){
        $resultado='';
        $enlace='';

        $consulta=$conexion->prepare($sql);
        $consulta->execute();
        $consulta->bind_result($id, $titulo, $contenido, $ruta_imagen, $fecha);
        
        while($consulta->fetch()){
            $contenido=substr($contenido, 0, 170);
            $enlace="noticia-comp.php?id=$id";
            
            //compruebo si estoy en el index para cambiar las rutas
            if($ruta_index){
                $ruta_imagen=str_replace('../../pics/', './pics/', $ruta_imagen);
                $enlace='./paginas/noticias/'.$enlace;
            }

            $resultado.="
            <a href='$enlace'>
                <article class='noticia' data-id='$id'>
                    <h2>$titulo</h2>
                    <div class='contenido-noticia'>
                        <div class='img-noticia'>
                            <img src='$ruta_imagen'>
                        </div>
                        <div class='side-text'>
                            <p>$contenido...</p>
                            <p>$fecha</p>
                        </div>
                    </div>
                </article>
            </a>";
        }

        $consulta->close();

        return $resultado;
    }

    function imprimirServicios($conexion, $sql){
        $resultado='';

        $consulta=$conexion->prepare($sql);
        $consulta->execute();
        $consulta->store_result(); 
        $consulta->bind_result($id, $descripcion, $duracion, $unidad_duracion, $precio);

 
            while($consulta->fetch()){
                $resultado.="
                    <div class='p-5 text-center bg-body-secondary rounded-4 custom-serv'>
                        <h1 class='text-body-emphasis'>$descripcion</h1>
                        <p class='lead'>Este servicio cuenta con una duración de <span>$duracion $unidad_duracion</span> y un precio de <span>$precio Euros</span>.</p>
                        <a href='servicios-mod.php?id=$id'>
                            <button class='btn btn-primary d-inline-flex align-items-center btn-custom'>
                                Modificar
                            </button>
                        </a>
                    </div>
                ";
        }
        return $resultado;
    }

    function imprimirServiciosPrep($conexion, $sql, $texto){
        $resultado='';

        $consulta=$conexion->prepare($sql);
        $consulta->bind_param("s", $texto);
        $consulta->execute();
        $consulta->store_result(); 
        $consulta->bind_result($id, $descripcion, $duracion, $unidad_duracion, $precio);

        if($consulta->num_rows>0){
            while($consulta->fetch()){
                $resultado.="
                    <div class='p-5 text-center bg-body-secondary rounded-4 custom-serv'>
                        <h1 class='text-body-emphasis'>$descripcion</h1>
                        <p class='lead'>Este servicio cuenta con una duración de <span>$duracion $unidad_duracion</span> y un precio de <span>$precio Euros</span>.</p>
                        <a href='servicios-mod.php?id=$id'>
                            <button class='btn btn-primary d-inline-flex align-items-center btn-custom'>
                                Modificar
                            </button>
                        </a>
                    </div>
                ";
            }
        }else{
            $resultado.="
                <h1 class='centrado'>No se han encontrado resultados...</h1>
            ";
        }
        
        return $resultado;
    }

    function listaCitas($consulta){
        $resultado='';

        $consulta->bind_result($nombre_socio, $nombre_servicio, $id_socio, $id_servicio, $fecha_cita, $hora, $cancel);
        if($consulta->num_rows > 0){
            while($consulta->fetch()){
                $resultado.="
                    <div class='cita'>
                        <div class='cita-contenido'>
                        <p><span class='resaltado'>Socio:</span> $nombre_socio</p>
                        <p><span class='resaltado'>Servicio:</span> $nombre_servicio</p>
                        <p><span class='resaltado'>Fecha:</span> $fecha_cita</p>
                        <p><span class='resaltado'>Hora:</span> $hora</p>";
                //segun el valor de $cancel (campo en la bd), imprimo un botón u otro entre cancelar o borrar, y establezco su acción pasando una variable 'accion' por GET
                if($cancel === 0){
                    $resultado.="<button class='actived'>Activa</button>
                                </div><div class='cita-btn'>
                                <a href='citas-confirm.php?socio=$id_socio&servicio=$id_servicio&fecha=$fecha_cita&hora=$hora&cancel=$cancel&action=c'>
                                    <button class='btn'>Cancelar</button>
                                </a>
                                ";
                }else{
                    $resultado.="<button class='cancelled'>Cancelada</button>
                                </div><div class='cita-btn'>
                                <a href='citas-confirm.php?socio=$id_socio&servicio=$id_servicio&fecha=$fecha_cita&hora=$hora&cancel=$cancel&action=d'>
                                    <button class='btn'>Borrar</button>
                                </a>
                                ";
                }
    
                $resultado.="</div>
                </div>";
            }
        }else{
            $resultado.="<h1 class='centrado'>No se han encontrado citas</h1>";
        }
        return $resultado;
    }

    /* ---------NUEVAS FUNCIONES--------- */

    /*
        Genera una Api Key cada vez que añado un usuario.
        Le paso el ID del socio generado (al que se asigna) por parámetro
    */
    function generarApiKey($conexion, $id){
        $api_key = bin2hex(random_bytes(32)); //genera la API Key de 64 caractéres

        $consulta = "INSERT INTO api_keys (id_socio, api_key) VALUES (?, ?)";
        $stmt = $conexion -> prepare($consulta);
        $stmt -> bind_param("is", $id, $api_key);

        if($stmt -> execute()){ //devuelve true o false en función de la ejecución exitosa de la consulta
            $stmt -> close();
            return $api_key;
        } else {
            $stmt -> close();
            throw new Exception("Error al generar la API Key");
        }
    }

    function generarListadoProductos($datos){
        $respuesta = "";
        $array_productos = $datos["datos"];
        foreach($array_productos as $prod){
            $respuesta .= "
                <article class='producto'>
                <h2>{$prod['nombre']}</h2>
                <img src='{$prod['imagen']}'>
                <p>Precio: {$prod['precio']} &#8364</p>
            ";

            if($prod['disponible'] != 1){
                $respuesta .= "<p>No disponible</p>";
            } else {
                $respuesta .= "<p>Disponible</p>";
            }

            $respuesta .= "
                <p>Categoría: {$prod['categoria']}</p>
                <section class='prod-options'>
                    <a href='productos-del.php?id={$prod['id']}' class='btn-modificar-php'>Eliminar</a>
                    <a href='productos-mod.php?id={$prod['id']}' class='btn-modificar-php'>Modificar</a>
                </section>
                </article>
            ";
        }
        return $respuesta;
    }


    function generarPaginadoProductos($datos, $parametros, $pagina){
        $respuesta = "<ul>";
        for($i = 1; $i <= $datos['total_paginas']; $i++){

            //COMPRUEBO SI EL PARÁMETRO PÁGINA VIENE EN LOS PARÁMETROS PARA NO DUPLICARLO
            if(!empty($parametros) || !empty($pagina)){
                if(empty($pagina)){
                    $respuesta .= "<li><a href='productos.php?$parametros&pagina=$i'>$i</a></li>";
                } elseif(empty($parametros)){
                    $respuesta .= "<li><a href='productos.php?pagina=$i'>$i</a></li>";
                } else {
                    $respuesta .= "<li><a href='productos.php?pagina=$i&$parametros'>$i</a></li>";
                }
            } else {
                $respuesta .= "<li><a href='productos.php?pagina=$i'>$i</a></li>";
            }
        }
        $respuesta .= "</ul>";

        return $respuesta;
    }


    function imprimirModificarProducto($id){
        $respuesta = "";
        $api_url = "http://localhost/club_karate/api/api.php?id=$id";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        $respuesta = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $datos = json_decode($respuesta, true);
        curl_close($ch);

        if($http_code != 200){
            $respuesta = "<h2>{$datos['error']}</h2>";
            return $respuesta;
        }
    
        $producto = $datos['datos'][0];
        $respuesta.="
            <div class='card-servicio'>
                <form action='productos-confirm.php' method='post' id='formulario-servicios' enctype='multipart/form-data>
                        <div class='avatar'><img src='{$producto['imagen']}' style='width: 300px'></div>
                        <textarea name='nombre' id='' placeholder='Nombre del producto'>{$producto['nombre']}</textarea>
                        <span class='error'></span>
                        <input type='text' name='precio' id='' value='{$producto['precio']}' placeholder='Precio'>
                        <span class='error'></span>
                        <textarea name='categoria' id='' placeholder='Categoría'>{$producto['categoria']}</textarea>
                        <span class='error'></span>
                        <input type='text' name='cantidad' id='' value='{$producto['cantidad']}' placeholder='Cantidad'>
                        <span class='error'></span>
                        <label class='input-file-custom'>
                                <input type='file' name='imagen' id='' accept='image/*'>
                                Subir imágen
                        </label>
                        
                        <input name='id' type='hidden' value='{$producto['id']}'>
                        <button class='btn btn-outline-secondary' type='submit'>Actualizar producto</button>
                </form>
            </div>
            ";

        return $respuesta;
    }