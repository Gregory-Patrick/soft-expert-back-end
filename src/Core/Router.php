<?php 

    namespace App\Core;

    /**
     * Classe Router
     *
     * Esta classe gerencia as rotas da aplicação e a lógica de roteamento.
     */
    class Router {
        
        /**
         * @var array As rotas registradas.
         */
        private $routes = [];

        /**
         * Adiciona uma rota à lista de rotas.
         *
         * @param string $method O método HTTP (GET, POST, etc.).
         * @param string $route A rota (URL) que deve ser correspondida.
         * @param callable|array $handler O manipulador para a rota. Pode ser uma função ou um array contendo o controlador e o método.
         */
        public function addRoute($method, $route, $handler) {
            $route = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
            $this->routes[] = [
                'method' => $method,
                'route' => $route,
                'handler' => $handler
            ];
        }

        /**
         * Executa o roteador para a URL da solicitação atual.
         */
        public function run() {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            
            foreach ($this->routes as $route) {
                if ($requestMethod == $route['method'] && preg_match('#^' . $route['route'] . '$#', $requestUri, $matches)) {
                    $handler = $route['handler'];
                    array_shift($matches);
                    
                    if (is_array($handler)) {
                        $controller = new $handler[0]();
                        $method = $handler[1];
                        call_user_func_array([$controller, $method], $matches);
                    } else {
                        call_user_func_array($handler, $matches);
                    }
                    return;
                }
            }

            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
        }
    }
?>
