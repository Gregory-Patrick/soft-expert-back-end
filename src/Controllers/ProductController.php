<?php

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Utils\Response;
    use App\Models\ProductModel;

    class ProductController {

        private $productModel;
        private $response;

        public function __construct() {
            $db = new Sql();
            $this->productModel = new ProductModel($db->getConnection());

            $this->response = new Response();
        }

        public function getAll() {
            if(!$products = $this->productModel->findAll()) {
                $this->response->simpleResponse(404, [
                    'success' => false,
                    'message' => 'No products were found'
                ]);
            }

            $this->response->objectResponse(200, $products);
        }

        public function getById() {
            $id = intval(basename($_SERVER['REQUEST_URI']));

            if(!$product = $this->productModel->findById($id)) {
                $this->response->simpleResponse(404, [
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }

            $this->response->objectResponse(200, $product);
        }

        public function create() {
            $data = json_decode(file_get_contents('php://input'), true);

            if(!isset($data['name']) || !isset($data['price'])) {
                $this->response->simpleResponse(400, [
                    'success' => false,
                    'message' => 'Mandatory parameters not received, name and price is mandatory'
                ]);
            }

            if($this->productModel->save($data)) {
                $this->response->simpleResponse(200, [
                    'success' => true,
                    'message' => 'Product registered successfully'
                ]);
            }

            $this->response->simpleResponse(500, [
                'success' => false,
                'message' => 'Something unexpected happened, try again later'
            ]);
        }

        public function update() {
            $id = intval(basename($_SERVER['REQUEST_URI']));
            $data = json_decode(file_get_contents('php://input'), true);

            if(!isset($data['name'])) {
                $data['name'] = $this->productModel->findById($id)['name'];
            }

            if($this->productModel->update($id, $data)) {
                $this->response->simpleResponse(200, [
                    'success' => true,
                    'message' => 'Product updated successfully'
                ]);
            }

            $this->response->simpleResponse(500, [
                'success' => false,
                'message' => 'Something unexpected happened, try again later'
            ]);
        }

        public function delete() {
            $id = intval(basename($_SERVER['REQUEST_URI']));

            if($this->productModel->findById($id)) {
                $this->productModel->delete($id);
                
                $this->response->simpleResponse(200, [
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ]);
            }

            $this->response->simpleResponse(404, [
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
    }

?>