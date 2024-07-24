<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once __DIR__ . '/vendor/autoload.php';

    use App\Core\Router;
    use App\Controllers\ProductController;

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Max-Age: 86400");

        http_response_code(204);
        exit();
    }

    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    $router = new Router();
    $router->addRoute('GET', '/api/products', [ProductController::class, 'getAll']);
    $router->addRoute('POST', '/api/products', [ProductController::class, 'create']);
    $router->addRoute('GET', '/api/products/{id}', [ProductController::class, 'getById']);
    $router->addRoute('PUT', '/api/products/{id}', [ProductController::class, 'update']);
    $router->addRoute('DELETE', '/api/products/{id}', [ProductController::class, 'delete']);

    $router->run();
?>