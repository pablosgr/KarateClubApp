<?php
    require_once '../vendor/autoload.php';
    use Dotenv\Dotenv;

    // Cargar el archivo .env
    $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
    $dotenv->load();

    // Obtener la clave desde las variables de entorno
    header('Content-Type: application/json');
    $apiKey = $_ENV['YOUTUBE_API_KEY'] ?? null;

    if ($apiKey) {
        echo json_encode(['apikey' => $apiKey]);
    } else {
        echo json_encode(['apikey' => 'No se ha encontrado la API Key']);
    }