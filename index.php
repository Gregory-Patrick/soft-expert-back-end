<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once __DIR__ . '/vendor/autoload.php';

    use App\Core\Router;
    use App\Controllers\ProductController;
    use App\Controllers\ProductTypeController;

    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Max-Age: 86400");

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(204);
        exit();
    }

    $router = new Router();
    $router->addRoute('GET', '/api/products', [ProductController::class, 'getAll']);
    $router->addRoute('POST', '/api/products', [ProductController::class, 'create']);
    $router->addRoute('GET', '/api/products/{id}', [ProductController::class, 'getById']);
    $router->addRoute('PUT', '/api/products/{id}', [ProductController::class, 'update']);
    $router->addRoute('DELETE', '/api/products/{id}', [ProductController::class, 'delete']);

    $router->addRoute('GET', '/api/types', [ProductTypeController::class, 'getAll']);
    $router->addRoute('POST', '/api/types', [ProductTypeController::class, 'create']);
    $router->addRoute('GET', '/api/types/{id}', [ProductTypeController::class, 'getById']);
    $router->addRoute('PUT', '/api/types/{id}', [ProductTypeController::class, 'update']);
    $router->addRoute('DELETE', '/api/types/{id}', [ProductTypeController::class, 'delete']);

    $router->run();
?>