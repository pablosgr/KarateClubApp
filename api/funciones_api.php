<?php

/*
    Lista todos los productos, y los busca si recibe parámetros de búsqueda.
    Recibe un paginado por defecto de límite 10 productos siempre como condición de la consulta.
    Mantiene los filtros de búsqueda en el paginado.
*/
function listarProductos($conexion, $condicion_sql, $datos){

    //EXTRAIGO LAS VARIABLES QUE NECESITO DEL ARRAY ASOC. $datos
    $pagina = $datos["pagina"];
    $limite_pagina = $datos["limite"];
    $parametros = $datos["parametros"];
    $tipos = $datos["tipos"];
    $filtros = $datos["filtros"];

    if($condicion_sql == ""){
        //CONSULTA PARA OBTENER EL TOTAL DE PRODUCTOS (EN CASO DE LISTADO COMPLETO)
        $consulta = "SELECT count(*) FROM productos";
		$resultado = $conexion -> query($consulta);
		$fila = $resultado -> fetch_row();
		$total_productos = $fila[0];
    } else {
        //SI HAY FILTROS, OBTENGO EL TOTAL DE RESULTADOS
        $consulta_total = "SELECT count(*) FROM productos " . $condicion_sql;
        $stmt_total = $conexion->prepare($consulta_total);
    
        if ($tipos != "") {
            $stmt_total->bind_param($tipos, ...$parametros);
        }
    
        $stmt_total->execute();
        $resultado_total = $stmt_total->get_result();
        $fila_total = $resultado_total->fetch_row();
        $total_productos = $fila_total[0];
        $stmt_total->close();
    }

    //AÑADO LOS PARÁMETROS DEL PAGINADO
    $offset = ($pagina - 1) * $limite_pagina;
    $condicion_sql .= " LIMIT ? OFFSET ?";
    $tipos .= "ii";
    $parametros = array_merge($parametros, [$limite_pagina, $offset]); //añado los parámetros de paginación al array de parámetros (siempre)

    $query = "SELECT * FROM productos" . $condicion_sql;
    $stmt = $conexion -> prepare($query);
    if($tipos != ""){
        $stmt -> bind_param($tipos, ...$parametros);
    }
    $stmt -> execute();
    $resultado = $stmt -> get_result();

    //CONSTRUYO LA URL CON LOS FILTROS (DE HABERLOS)
    $url_base = "http://localhost/club_karate/api/api.php?";
    foreach($filtros as $filtro => $valor){
        if($filtro != "pagina" && $filtro != "limite"){
            //OBVIO LAS VARIABLES DE PÁGINA Y LÍMITE AQUÍ, PUES LAS INCLUYO MÁS ADELANTE EN LA RESPUESTA DE LA API
            $url_base .= $filtro;
            $url_base .= "=" . $valor . "&";
        }
    }
    
    //SI HAY RESULTADOS, LOS IMPRIMO
    if($resultado -> num_rows > 0){
        $data = [];

        while($row = $resultado -> fetch_assoc()){
            $data[] = [
                "id" => $row["id"],
                "nombre" => $row["nombre"],
                "precio" => $row["precio"],
                "categoria" => $row["categoria"],
                "imagen" => $row["imagen"],
                "disponible" => $row["disponible"],
                "cantidad" => $row["cantidad"]
            ];
        }

        $response["http"] = 200;
        $response["respuesta"] = [
            "datos" => $data,
            "pagina" => $pagina,
            "limite" => $limite_pagina,
            "siguiente" => $pagina < ceil($total_productos / $limite_pagina) ? $url_base . "pagina=".($pagina + 1)."&limite=".$limite_pagina : null,
            "anterior" => $pagina > 1 ? $url_base . "pagina=".($pagina - 1)."&limite=".$limite_pagina : null,
            "total_paginas" => ceil($total_productos / $limite_pagina),
            "total_resultados" => $total_productos
        ];
    } else {
        $response["http"] = 404;
        $response["respuesta"] = ["error" => "No se han encontrado productos"];
    }

    $stmt -> close();
    return $response;
}

/*
    Añade un producto, obteniendo los datos del mismo de un array asociativo pasado por parámetro
*/
function addProducto($conexion, $datos_producto){
    //EXTRAIGO LAS VARIABLES DE $datos_producto
    $nombre = $datos_producto["nombre"];
    $precio = (float)$datos_producto["precio"];
    $categoria = $datos_producto["categoria"];
    $cantidad = isset($datos_producto["cantidad"]) ? (int)$datos_producto["cantidad"] : 1; //Si no existe el campo, pongo 1 por defecto

    if(trim($nombre) != "" && $precio > 0 && trim($categoria) != "" && $cantidad > 0){
        
        //CONSULTA DE COMPROBACIÓN
        $check_query = "SELECT * FROM productos WHERE nombre = ?";
        $result = $conexion -> prepare($check_query);
        $result -> bind_param("s", $nombre);
        $result -> execute();
        $resultado = $result -> get_result();

        if($resultado -> num_rows > 0){
            $response["http"] = 409; //duplicidad
			$response["respuesta"] = ["error" => "El nombre de producto ya existe"];
        } else {
            //SI PASA LA COMPROBACIÓN, HAGO EL INSERT
            $consulta = "INSERT INTO productos (nombre, precio, categoria, cantidad) VALUES (?, ?, ?, ?)";
            $stmt = $conexion -> prepare($consulta);
            $stmt -> bind_param("sdsi", $nombre, $precio, $categoria, $cantidad);
            $stmt -> execute();
            
            if($conexion -> affected_rows > 0){
                $response["http"] = 200;
                $response["respuesta"] = [
                    "id" => $stmt -> insert_id, //obtenemos el id generado de forma incremental para devolverlo en la respuesta
                    "producto_insertado" => $nombre
                ];
            }
			
		    $stmt -> close();
        }

        $result -> close();
    } else {
        $response["http"] = 400;
        $response["respuesta"] = ["error" => "Datos de entrada no válidos"];
    }

    return $response;
}

/*
    Modifica un producto, obteniendo los datos del mismo de un array asociativo pasado por parámetro, obtenidos por POST
*/
function modificarProducto($conexion, $id, $datos_producto){
    //EXTRAIGO LAS VARIABLES DE $datos_producto
    $id = (int)$id;
    $nombre = $datos_producto["nombre"];
    $precio = (float)$datos_producto["precio"];
    $categoria = $datos_producto["categoria"];
    $cantidad = (int)$datos_producto["cantidad"];

    if(trim($nombre) != "" && $precio > 0 && trim($categoria) != "" && $cantidad > 0){
        
        //CONSULTA DE COMPROBACIÓN
        $check_query = "SELECT * FROM productos WHERE nombre = ? AND id != ?";
        $result = $conexion -> prepare($check_query);
        $result -> bind_param("si", $nombre, $id);
        $result -> execute();
        $resultado = $result -> get_result();

        if($resultado -> num_rows > 0){
            $response["http"] = 409; //duplicidad
			$response["respuesta"] = ["error" => "El nombre de producto ya existe"];
        } else {
            //SI PASA LA COMPROBACIÓN, HAGO EL UPDATE
            $consulta = "UPDATE productos SET nombre = ?, precio = ?, categoria = ?, cantidad = ? WHERE id = ?";
            $stmt = $conexion -> prepare($consulta);
            $stmt -> bind_param("sdsii", $nombre, $precio, $categoria, $cantidad, $id);
            $stmt -> execute();
            
            if($conexion -> affected_rows > 0){
                $response["http"] = 200;
                $response["respuesta"] = [
                    "producto_modificado" => $nombre
                ];
            } else {
                $response["http"] = 200;
                $response["respuesta"] = ["error" => "No se han modificado datos"];
            }
                
            $stmt -> close();
        }
        
        $result -> close();
    } else {
        $response["http"] = 400;
        $response["respuesta"] = ["error" => "Datos de entrada no válidos"];
    }

    return $response;
}

/*
    Elimina un producto por ID, obtenida por GET
*/
function eliminarProducto($conexion, $id){

    if(is_numeric($id)){
        $id = (int)$id;
        
        $consulta = "DELETE FROM productos WHERE id = ?";
        $stmt = $conexion -> prepare($consulta);
        $stmt -> bind_param("i", $id);
        $stmt -> execute();
            
        if($conexion -> affected_rows > 0){
            $response["http"] = 200;
            $response["respuesta"] = [
                "exito" => "Producto eliminado"
            ];
        } else {
            $response["http"] = 404; //not found
            $response["respuesta"] = ["error" => "Producto no encontrado"];
        }
                
        $stmt -> close();
    } else {
        $response["http"] = 400;
        $response["respuesta"] = ["error" => "Datos de entrada no válidos"];
    }

    return $response;
}

/*
    Valida la API Key, la llamo en cada petición
*/
function validarApiKey($conexion, $api_key){
    $consulta = "SELECT * FROM api_keys WHERE api_key = ? AND active = 1";
    $stmt = $conexion -> prepare($consulta);
    $stmt -> bind_param("s", $api_key);
    $stmt -> execute();
    $resultado = $stmt -> get_result();

    if($resultado -> num_rows > 0){
        $stmt -> close();
        return true;
    } else {
        $stmt -> close();
        return false;
    }
}