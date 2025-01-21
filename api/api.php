<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "../php/config.php";
require_once "../php/funciones.php";
require_once "funciones_api.php";

try {
    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db_tienda);
} catch (mysqli_sql_exception $err) {
    http_response_code(500);
    echo json_encode(["error" => "Error al conectar con la BD"]);
}

$metodo = $_SERVER["REQUEST_METHOD"];
if($metodo == "POST" || $metodo == "PUT"){
    $entrada = json_decode(file_get_contents("php://input"), true);
}

switch($metodo){
    case "GET":
        //SE ESTABLECEN LAS VARIABLES

        $nombre_busqueda = $_GET["nombre"] ?? null;
        $precio_busqueda = $_GET["precio"] ?? null;
        $categoria_busqueda = $_GET["categoria"] ?? null;
        $disponibilidad_busqueda = $_GET["disponible"] ?? null;
        $pagina = isset($_GET["pagina"]) && is_numeric($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
        $limite_pagina = isset($_GET["limite"]) && is_numeric($_GET["limite"]) ? (int)$_GET["limite"] : 10;

        $condicion_sql = "";
        $tipos = "";
        $params = [];

        //COMPROBACIONES DE VARIABLES PARA FORMAR LA CONDICIÓN DE LA CONSULTA

        if(isset($nombre_busqueda) && isset($nombre_busqueda) != ""){
            $condicion_sql .= $condicion_sql == "" ? " WHERE nombre LIKE ?" : " AND nombre LIKE ?";
            $tipos .= "s";
            $params[] = "%" . $nombre_busqueda . "%";
        }

        if(isset($precio_busqueda)){
            if(is_numeric($precio_busqueda)){
                $precio_busqueda = (float)$precio_busqueda; //cast a float, pues cualquier valor pasado por GET se toma como String
                $condicion_sql .= $condicion_sql == "" ? " WHERE precio <= ?" : " AND precio <= ?";
                $tipos .= "d"; //d para float
                $params[] = $precio_busqueda;
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
        } else {
            $offset = ($pagina - 1) * $limite_pagina;
            $condicion_sql .= " LIMIT ? OFFSET ?";
            $tipos .= "ii";
            $params = array_merge($params, [$limite_pagina, $offset]);
        }

        //LLAMADA A LA FUNCIÓN QUE REALIZA LA CONSULTA
        
        $resultado = listarProductos($conexion, $condicion_sql, $tipos, $params);
        http_response_code($resultado["http"]);
        echo json_encode($resultado["respuesta"]);

        break;
}