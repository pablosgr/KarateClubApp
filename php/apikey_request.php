<?php
    require_once '../vendor/autoload.php'; //lo importo para acceder a las variables de .env
    use Dotenv\Dotenv;

    // cargo el archivo .env a partir del directorio actual (__DIR__) y subiendo dos niveles con dirname()
    $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
    $dotenv->load();
    
    header('Content-Type: application/json');
    $apiKey = $_ENV['YOUTUBE_API_KEY'] ?? null; //accedo a la variable de entorno con la API Key

    if ($apiKey) {
        echo json_encode(['apikey' => $apiKey]);
    } else {
        echo json_encode(['apikey' => 'No se ha encontrado la API Key']);
    }