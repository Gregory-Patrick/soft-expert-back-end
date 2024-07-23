<?php
    require_once __DIR__ . '/vendor/autoload.php';

    // Configurações (por exemplo, conexão com o banco de dados, configurações de ambiente)
    // require_once __DIR__ . '/../config/config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Max-Age: 86400");

        http_response_code(204);
        exit(0);
    }

    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    use App\Core\Router;

    $router = new Router();
    $router->addRoute('GET', '/api/products', 'ProductController@getAll');
    $router->addRoute('POST', '/api/products', 'ProductController@create');
    $router->addRoute('GET', '/api/products/{id}', 'ProductController@getById');
    $router->addRoute('PUT', '/api/products/{id}', 'ProductController@update');
    $router->addRoute('DELETE', '/api/products/{id}', 'ProductController@delete');

    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    try {
        $router->dispatch($requestMethod, $requestUri);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

?>