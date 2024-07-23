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

        public function dispatch($method, $uri) {
            foreach ($this->routes as $route) {
                if ($route['method'] === $method && $this->matchRoute($uri, $route['route'])) {
                    $this->handle($route['handler']);
                    return;
                }
            }

            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }

        private function matchRoute($uri, $route) {
            $routePattern = preg_replace('/\{[^\/]+\}/', '([^\/]+)', $route);
            $routePattern = str_replace('/', '\/', $routePattern);
            return preg_match('/^' . $routePattern . '$/', $uri);
        }

        private function handle($handler) {
            list($controllerName, $methodName) = explode('@', $handler);
            
            $controllerClass = 'App\Controllers\\' . $controllerName;
            if (!class_exists($controllerClass)) {
                http_response_code(500);
                echo json_encode(['error' => 'Controller not found']);
                return;
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $methodName)) {
                http_response_code(500);
                echo json_encode(['error' => 'Method not found']);
                return;
            }

            $controller->$methodName();
        }
    }

?>