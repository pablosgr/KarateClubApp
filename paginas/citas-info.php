<?php
    require_once '../php/funciones.php';
    require_once '../php/config.php';
    require_once '../php/traduccion.php';
    $conexion=conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

    if(isset($_GET["fecha"])){
        $fecha=$_GET["fecha"];
    }
    echo mostrarCitas($conexion, $fecha);
?>