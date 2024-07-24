<?php 

    namespace App\Utils;

    class Response {
       
        public function simpleResponse(int $httpCode, array $response) {
            http_response_code($httpCode);
            echo json_encode($response);
            exit();
        }

        public function objectResponse(int $httpCode, array $object) {
            http_response_code($httpCode);
            echo json_encode($object);
            exit();
        }
    }

?>