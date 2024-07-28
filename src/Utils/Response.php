<?php 

    namespace App\Utils;

    /**
     * Classe Response
     *
     * Esta classe fornece métodos para criar e enviar respostas HTTP em formato JSON.
     */
    class Response {

        /**
         * Envia uma resposta simples com um código HTTP e uma mensagem.
         *
         * @param int $httpCode O código HTTP da resposta.
         * @param string $message A mensagem a ser enviada na resposta.
         * @return void
         */
        public function setSimpleResponse(int $httpCode, string $message) {
            $response = [
                'success' => false,
                'message' => $message
            ];

            if ($httpCode === 200 || $httpCode === 204) {
                $response['success'] = true;
            }
            $this->simpleResponse($httpCode, $response);
        }

        /**
         * Envia uma resposta com um objeto e um código HTTP.
         *
         * @param int $httpCode O código HTTP da resposta.
         * @param array $object O objeto a ser enviado na resposta.
         * @return void
         */
        public function setObjectResponse(int $httpCode, array $object) {
            $response = [
                'success' => false,
                'data' => $object
            ];

            if($httpCode === 200) {
                $response['success'] = true;
            }
            $this->objectResponse($httpCode, $response);
        }

        /**
         * Envia uma resposta simples em formato JSON com um código HTTP.
         *
         * @param int $httpCode O código HTTP da resposta.
         * @param array $response O array contendo a resposta.
         * @return void
         */
        public function simpleResponse(int $httpCode, array $response) {
            http_response_code($httpCode);
            echo json_encode($response);
            exit();
        }

        /**
         * Envia uma resposta em formato JSON com um objeto e um código HTTP.
         *
         * @param int $httpCode O código HTTP da resposta.
         * @param array $object O objeto a ser enviado na resposta.
         * @return void
         */
        public function objectResponse(int $httpCode, array $object) {            
            http_response_code($httpCode);
            echo json_encode($object);
            exit();
        }

        /**
         * Obtém o ID do item da solicitação a partir da URI.
         *
         * @return int O ID do item.
         */
        public function getItemRequestId() {
            return intval(basename($_SERVER['REQUEST_URI']));
        }

        /**
         * Obtém os dados da solicitação em formato JSON.
         *
         * @return array Os dados da solicitação.
         */
        public function getDataRequest() {
            return json_decode(file_get_contents('php://input'), true);
        }
    }

?>