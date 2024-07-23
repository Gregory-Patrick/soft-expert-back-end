<?php 

    namespace App\Core;

    class Router {
        private $routes = [];

        public function addRoute($method, $route, $handler) {
            $this->routes[] = [
                'method' => $method,
                'route' => $route,
                'handler' => $handler
            ];
        }

        public function run() {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
            foreach ($this->routes as $route) {
                if ($requestMethod == $route['method'] && preg_match('#^' . $route['path'] . '$#', $requestUri, $matches)) {
                    $handler = $route['handler'];
                    if (is_array($handler)) {
                        $controller = new $handler[0]();
                        $method = $handler[1];
                        call_user_func_array([$controller, $method], array_slice($matches, 1));
                    } else {
                        $handler();
                    }
                    return;
                }
            }
    
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
        }
    }

?>