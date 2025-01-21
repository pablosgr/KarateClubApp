<?php

function listarProductos($conexion, $condicion_sql, $tipos, $parametros){
    $query = "SELECT * FROM productos" . $condicion_sql;
    $stmt = $conexion -> prepare($query);
    if($tipos != ""){
        $stmt -> bind_param($tipos, ...$parametros);
    }
    $stmt -> execute();
    $resultado = $stmt -> get_result();
    if($resultado -> num_rows > 0){
        $data = [];

        while($row = $resultado -> fetch_assoc()){
            $data[] = [
                "id_producto" => $row["id"],
                "nombre" => $row["nombre"],
                "precio" => $row["precio"],
                "categoria" => $row["categoria"],
                "disponibilidad" => $row["disponible"],
                "stock" => $row["cantidad"]
            ];
        }

        $response["http"] = 200;
        $response["respuesta"] = [
            "datos" => $data
        ];
    } else {
        $response["http"] = 404;
        $response["respuesta"] = ["error" => "No se han encontrado productos"];
    }

    $stmt -> close();
    return $response;
}