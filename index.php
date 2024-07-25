<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once __DIR__ . '/vendor/autoload.php';

    use App\Core\Router;
    use App\Controllers\ProductController;
    use App\Controllers\ProductTypeController;
use App\Controllers\TaxController;

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

    $router->addRoute('GET', '/api/tax', [TaxController::class, 'getAll']);
    $router->addRoute('POST', '/api/tax', [TaxController::class, 'create']);
    $router->addRoute('GET', '/api/tax/{id}', [TaxController::class, 'getById']);
    $router->addRoute('PUT', '/api/tax/{id}', [TaxController::class, 'update']);
    $router->addRoute('DELETE', '/api/tax/{id}', [TaxController::class, 'delete']);

    $router->run();
?>