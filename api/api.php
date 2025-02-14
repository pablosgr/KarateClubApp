<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "../php/config.php";
require_once "../php/funciones.php";
require_once "funciones_api.php";

try {
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);
} catch (mysqli_sql_exception $err) {
    http_response_code(500);
    echo json_encode(["error" => "Error al conectar con la BD"]);
}

$metodo = $_SERVER["REQUEST_METHOD"];
if($metodo == "POST" || $metodo == "PUT"){
    //OBTENGO LOS PARÁMETROS EN CASO DE HACER UN INSERT O UN UPDATE
    $entrada = json_decode(file_get_contents("php://input"), true);
}

switch($metodo){
    case "GET":
        //SE ESTABLECEN LAS VARIABLES; PARA PAGINADO: pagina 1, limite 10 por defecto

        $id_busqueda = $_GET["id"] ?? null;
        $nombre_busqueda = $_GET["nombre"] ?? null;
        $precio_busqueda_superior = $_GET["precioSup"] ?? null;
        $precio_busqueda_inferior = $_GET["precioInf"] ?? null;
        $categoria_busqueda = $_GET["categoria"] ?? null;
        $disponibilidad_busqueda = $_GET["disponible"] ?? null;
        $pagina = isset($_GET["pagina"]) && is_numeric($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
        $limite_pagina = isset($_GET["limite"]) && is_numeric($_GET["limite"]) ? (int)$_GET["limite"] : 9;

        $condicion_sql = "";
        $tipos = "";
        $params = [];

        //COMPROBACIONES DE VARIABLES PARA FORMAR LA CONDICIÓN DE LA CONSULTA

        if(isset($id_busqueda)){
            if(!is_numeric($id_busqueda)){
                http_response_code(400); //bad request
                echo json_encode(["error" => "Error en el id, debe ser un número"]);
                die(); //si no hago die(), el código seguirá ejecutándose
            }

            $condicion_sql .= $condicion_sql == "" ? " WHERE id = ?" : " AND id = ?";
            $tipos .= "i";
            $params[] = $id_busqueda;
        }

        if(isset($nombre_busqueda) && isset($nombre_busqueda) != ""){
            $condicion_sql .= $condicion_sql == "" ? " WHERE nombre LIKE ?" : " AND nombre LIKE ?";
            $tipos .= "s";
            $params[] = "%" . $nombre_busqueda . "%";
        }

        if(isset($precio_busqueda_inferior)){
            if(is_numeric($precio_busqueda_inferior)){
                $precio_busqueda_inferior = (float)$precio_busqueda_inferior; //cast a float, pues cualquier valor pasado por GET se toma como String
                $condicion_sql .= $condicion_sql == "" ? " WHERE precio <= ?" : " AND precio <= ?";
                $tipos .= "d"; //d para float
                $params[] = $precio_busqueda_inferior;
            } else {
                http_response_code(400); //bad request
                echo json_encode(["error" => "Error en el precio, valor no admitido"]);
                die(); //si no hago die(), el código seguirá
            }
        }

        if(isset($precio_busqueda_superior)){
            if(is_numeric($precio_busqueda_superior)){
                $precio_busqueda_superior = (float)$precio_busqueda_superior; //cast a float, pues cualquier valor pasado por GET se toma como String
                $condicion_sql .= $condicion_sql == "" ? " WHERE precio >= ?" : " AND precio >= ?";
                $tipos .= "d"; //d para float
                $params[] = $precio_busqueda_superior;
            } else {
                http_response_code(400); //bad request
                echo json_encode(["error" => "Error en el precio, valor no admitido"]);
                die(); //si no hago die(), el código seguirá
            }
        }

        if(isset($categoria_busqueda) && isset($categoria_busqueda) != ""){
            $condicion_sql .= $condicion_sql == "" ? " WHERE categoria LIKE ?" : " AND categoria LIKE ?";
            $tipos .= "s";
            $params[] = "%" . $categoria_busqueda . "%";
        }

        if(isset($disponibilidad_busqueda)){
            if($disponibilidad_busqueda != 0 && $disponibilidad_busqueda != 1){
                http_response_code(400); //bad request
                echo json_encode(["error" => "Error en la disponibilidad, valor no admitido"]);
                die();
            } else {
                $disponibilidad_busqueda = (int)$disponibilidad_busqueda;
                $condicion_sql .= $condicion_sql == "" ? " WHERE disponible = ?" : " AND disponible LIKE ?";
                $tipos .= "i";
                $params[] = $disponibilidad_busqueda;
            }
        }

        if($pagina < 1 || $limite_pagina < 1){
            http_response_code(400); //bad request
            echo json_encode(["error" => "Valores de paginación no válidos"]);
            die();
        }

        $datos = array(
            "tipos" => $tipos,
            "parametros" => $params,
            "pagina" => $pagina,
            "limite" => $limite_pagina,
            "filtros" => $_GET //paso todos los parámetros que tenga por GET para mantenerlos en el paginado
        );

        //LLAMADA A LA FUNCIÓN QUE REALIZA LA CONSULTA
        
        $resultado = listarProductos($conexion, $condicion_sql, $datos);
        http_response_code($resultado["http"]);
        echo json_encode($resultado["respuesta"]);

        break;
    
    case "POST":
        //USO $entrada PARA OBTENER LOS VALORES PASADOS POR POST
        if(isset($entrada["nombre"]) 
        && isset($entrada["precio"]) 
        && isset($entrada["categoria"])){

            $producto = array(
                "nombre" => $entrada["nombre"],
                "precio" => $entrada["precio"],
                "categoria" => $entrada["categoria"]
            );

            if(isset($entrada["cantidad"]) && $entrada["cantidad"] != 0){
                $producto = array_merge($producto, ["cantidad" => $entrada["cantidad"]]);
            }

            if(isset($entrada["imagen"])){
                $producto = array_merge($producto, ["imagen" => $entrada["imagen"]]);
            }

            $resultado = addProducto($conexion, $producto);
            http_response_code($resultado["http"]);
            echo json_encode($resultado["respuesta"]);
        } else {
            http_response_code(400);
			echo json_encode(["error" => "Faltan datos para el método POST"]);
        }

        break;

    case "PUT":
        //USO $entrada PARA OBTENER LOS VALORES PASADOS POR POST (obtengo el id por post también, es mejor no combinar envío de datos por diferentes vías)
        if(isset($entrada["id"]) 
        && isset($entrada["nombre"]) 
        && isset($entrada["precio"]) 
        && isset($entrada["categoria"]) 
        && isset($entrada["cantidad"])){

            $producto = array(
                "nombre" => $entrada["nombre"],
                "precio" => $entrada["precio"],
                "categoria" => $entrada["categoria"],
                "cantidad" => $entrada["cantidad"]
            );

            if (isset($entrada["imagen"])) {
                $producto["imagen"] = $entrada["imagen"];
            }
            
            $resultado = modificarProducto($conexion, $entrada["id"], $producto);
            http_response_code($resultado["http"]);
            echo json_encode($resultado["respuesta"]);
        } else {
            http_response_code(400);
			echo json_encode(["error" => "Faltan datos para el método PUT"]);
        }

        break;

        case "DELETE":

            if(isset($_GET["id"])){
                $resultado = eliminarProducto($conexion, $_GET["id"]);
                http_response_code($resultado["http"]);
                echo json_encode($resultado["respuesta"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Faltan datos para el método DELETE"]);
            }
    
            break;
        
            default:
                http_response_code(405); //bad request
                json_encode(["error" => "Método no soportado"]);

}

$conexion -> close();