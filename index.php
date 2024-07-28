<?php
    require_once __DIR__ . '/vendor/autoload.php';

    use App\Core\Router;
    use App\Controllers\SaleController;
    use App\Controllers\ProductController;
    use App\Controllers\ProductTaxController;
    use App\Controllers\ProductTypeController;

    /**
     * Configuração de cabeçalhos CORS.
     */
    $allowedOrigins = [
        'http://localhost:3000',
        'https://soft-expert-front-end.vercel.app'
    ];

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        }
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 86400");
    }

    /**
     * Tratamento de requisições OPTIONS para CORS.
     */
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        }
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
        http_response_code(204);
        exit();
    }

    $router = new Router();

    // Definição de rotas para produtos
    $router->addRoute('GET', '/api/products', [ProductController::class, 'getAll']);
    $router->addRoute('POST', '/api/products', [ProductController::class, 'create']);
    $router->addRoute('GET', '/api/products/{id}', [ProductController::class, 'getById']);
    $router->addRoute('PUT', '/api/products/{id}', [ProductController::class, 'update']);
    $router->addRoute('DELETE', '/api/products/{id}', [ProductController::class, 'delete']);

    // Definição de rotas para tipos de produtos
    $router->addRoute('GET', '/api/types', [ProductTypeController::class, 'getAll']);
    $router->addRoute('POST', '/api/types', [ProductTypeController::class, 'create']);

    // Definição de rotas para impostos sobre produtos
    $router->addRoute('POST', '/api/tax', [ProductTaxController::class, 'create']);
    $router->addRoute('GET', '/api/tax/{id}', [ProductTaxController::class, 'getById']);

    // Definição de rotas para vendas
    $router->addRoute('GET', '/api/sale', [SaleController::class, 'getAll']);
    $router->addRoute('POST', '/api/sale', [SaleController::class, 'create']);

    // Executa o roteador para despachar a rota correspondente
    $router->run();
?>